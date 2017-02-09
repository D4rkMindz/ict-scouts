<?php

namespace AppBundle\Service;

use AppBundle\Entity\Group;
use AppBundle\Entity\Person;
use AppBundle\Entity\Scout;
use AppBundle\Entity\Talent;
use AppBundle\Entity\TalentStatus;
use AppBundle\Entity\TalentStatusHistory;
use AppBundle\Entity\User;
use AppBundle\Repository\TalentStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use phpDocumentor\Reflection\Types\Array_;
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
     */
    public function getAllUsers($domain)
    {
        $this->googleService->auth(GoogleService::SERVICE);
        $this->googleService->getClient()->setSubject($this->googleService->getAdminUser());

        $service = new \Google_Service_Directory($this->googleService->getClient());

        $users = $service->users->listUsers(['domain' => $domain])->getUsers();

        /** @var \Google_Service_Directory_User $user */
        foreach ($users as $user) {
            $dbUser = $this->em->getRepository('AppBundle:User')->findOneBy(['googleId' => $user->getId()]);

            if (!$dbUser) {
                $dbUser = new User();
                $dbUser->setGoogleId($user->getId());
                $dbUser->setEmail($user->getPrimaryEmail());
                $this->em->persist($dbUser);
            }

            $this->updateUser($dbUser, $user);

            $this->em->flush();
        }
    }

    /**
     * Update user.
     *
     * @param User                           $user
     * @param \Google_Service_Directory_User $googleUser
     */
    private function updateUser(User $user, \Google_Service_Directory_User $googleUser)
    {
        $groups = $this->updateUserGroups($user, $googleUser->getOrgUnitPath());

        if (in_array($this->scoutGroup, $groups)) {
            $scout = $user->getScout();

            if (!$scout) {
                $scout = new Scout($this->createPerson($googleUser), $user);

                $this->em->persist($scout);
                $this->em->flush();
            }
        }

        if (in_array($this->talentGroup, $groups)) {
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
     * @return array
     */
    public function updateUserGroups(User &$user, $ou) : array
    {
        $group = null;
        $userGroups = (!$user->getGroups() ? [] : $user->getGroups());

        if ('/Support' == $ou && !in_array($this->adminGroup, $userGroups)) {
            $group = $this->adminGroup;
        } elseif ('/Scouts' == $ou && !in_array($this->scoutGroup, $userGroups)) {
            $group = $this->scoutGroup;
        } elseif ('/ict-campus/ICT Talents' == $ou && !in_array($this->talentGroup, $userGroups)) {
            $group = $this->talentGroup;
        }

        if ($group) {
            $user->addGroup($group);
            $this->em->persist($user);
        } else {
            $user->setGroups($userGroups);
        }

        return $user->getGroups();
    }

    /**
     * Create person object.
     *
     * @param \Google_Service_Directory_User $googleUser
     *
     * @return Person
     */
    private function createPerson(\Google_Service_Directory_User $googleUser)
    {
        $name = $googleUser->getName();

        /** @var Person $person */
        $person = new Person();
        $person->setFamilyName($name->getFamilyName());
        $person->setGivenName($name->getGivenName());
        $person->setMail($googleUser->getPrimaryEmail());

        $this->em->persist($person);
        $this->em->flush();

        return $person;
    }

    /**
     * Update AccessToken data.
     *
     * @param int   $googleId
     * @param array $accessToken =false
     *
     * @return bool
     */
    public function updateUserAccessToken($googleId, $accessToken): bool
    {
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['googleId' => $googleId]);

        if ($user) {
            if ($accessToken) {
                $user->setAccessToken($accessToken['access_token']);
                $user->setAccessTokenExpireDate(
                    (new \DateTime())->add(new \DateInterval('PT'.($accessToken['expires_in'] - 5).'S'))
                );
            }

            $this->em->persist($user);
            $this->em->flush();

            return true;
        } else {
            return false;
        }
    }
}