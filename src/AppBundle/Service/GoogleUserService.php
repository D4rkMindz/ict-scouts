<?php

namespace AppBundle\Service;

use AppBundle\Entity\Group;
use AppBundle\Entity\Person;
use AppBundle\Entity\Scout;
use AppBundle\Entity\Talent;
use AppBundle\Entity\TalentStatus;
use AppBundle\Entity\TalentStatusHistory;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class GoogleUserService.
 */
class GoogleUserService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var GoogleService
     */
    private $googleService;

    /**
     * @var Group
     */
    private $adminGroup;

    /**
     * @var Group
     */
    private $scoutGroup;

    /**
     * @var Group
     */
    private $talentGroup;

    /**
     * GoogleUserService constructor.
     *
     * @param Kernel        $kernel
     * @param EntityManager $entityManager
     */
    public function __construct(Kernel $kernel, EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->googleService = $kernel->getContainer()->get('app.service.google');

        $this->adminGroup = $this->em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_ADMIN']);
        $this->scoutGroup = $this->em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_SCOUT']);
        $this->talentGroup = $this->em->getRepository('AppBundle:Group')->findOneBy(['role' => 'ROLE_TALENT']);
    }

    /**
     * Get googleService.
     *
     * @return GoogleService
     */
    public function getGoogleService()
    {
        return $this->googleService;
    }

    /**
     * Get all users from provided domain.
     *
     * @param $domain
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Exception
     */
    public function getAllUsers($domain)
    {
        $this->googleService->auth(GoogleService::SERVICE);
        $this->googleService->getClient()->setSubject($this->googleService->getAdminUser());

        $service = new \Google_Service_Directory($this->googleService->getClient());

        /** @var \Google_Service_Directory_Users $users */
        $users = $service->users->listUsers(['domain' => $domain])->getUsers();

        /** @var \Google_Service_Directory_User $user */
        foreach ($users as $user) {
            $dbUser = $this->em->getRepository('AppBundle:User')->findOneBy(['googleId' => $user->getId()]);

            if (!$dbUser) {
                $dbUser = new User($user->getId(), $user->getPrimaryEmail());
                $this->em->persist($dbUser);
                $this->em->flush();
            }

            $this->updateUser($dbUser, $user);
        }
    }

    /**
     * Update user.
     *
     * @param User                           $user
     * @param \Google_Service_Directory_User $googleUser
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    private function updateUser(User $user, \Google_Service_Directory_User $googleUser)
    {
        $this->updateUserGroups($user, $googleUser->getOrgUnitPath());

        if ($user->getGroups()->contains($this->scoutGroup)) {
            $scout = $user->getScout();

            if (!$scout) {
                $scout = new Scout($user);

                $this->em->persist($scout);
                $this->em->flush();
            }
        }

        if ($user->getGroups()->contains($this->talentGroup)) {
            $talent = $user->getTalent();

            if (!$talent) {
                $talentStatus = $this->em->getRepository('AppBundle:TalentStatus')->find(TalentStatus::ACTIVE);

                $talent = new Talent($this->createPerson($googleUser), $user);

                $this->em->persist($talent);
                $this->em->flush();

                $talentStatusHistory = new TalentStatusHistory($talent, $talentStatus);

                $this->em->persist($talentStatusHistory);
                $this->em->flush();
            }
        }
    }

    /**
     * Set group based on Google organisation unit.
     *
     * @param User   $user
     * @param string $ou
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function updateUserGroups(User $user, $ou)
    {
        $group = null;
        $userGroups = (!$user->getGroups() ? 'foo' : $user->getGroups());

        if ('/Support' === $ou && !$userGroups->contains($this->adminGroup)) {
            $group = $this->adminGroup;
        } elseif ('/Scouts' === $ou && !$userGroups->contains($this->scoutGroup)) {
            $group = $this->scoutGroup;
        } elseif ('/ict-campus/ICT Talents' === $ou && !$userGroups->contains($this->talentGroup)) {
            $group = $this->talentGroup;
        }

        if ($group) {
            $user->addGroup($group);
            $this->em->persist($user);
        }
    }

    /**
     * Create person object.
     *
     * @param \Google_Service_Directory_User $googleUser
     *
     * @return Person
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    private function createPerson(\Google_Service_Directory_User $googleUser)
    {
        $name = $googleUser->getName();

        /** @var Person $person */
        $person = new Person($name->getFamilyName(), $name->getGivenName());
        $person->setMail($googleUser->getPrimaryEmail());

        $this->em->persist($person);
        $this->em->flush();

        return $person;
    }

    /**
     * Update AccessToken data.
     *
     * @param int   $googleId
     * @param array $accessToken =null
     *
     * @return bool
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    public function updateUserAccessToken($googleId, array $accessToken = null): bool
    {
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleId]);

        if ($user) {
            if ($accessToken) {
                $user->setAccessToken($accessToken['access_token']);
                $user->setAccessTokenExpireDate(
                    (new \DateTime())->add(new \DateInterval('PT'.($accessToken['expires_in'] - 5).'S'))
                );

                $this->em->persist($user);
                $this->em->flush();
            }

            return true;
        } else {
            return false;
        }
    }
}
