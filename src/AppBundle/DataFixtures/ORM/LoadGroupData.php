<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadGroupData.
 */
class LoadGroupData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $talent = new Group('Talent', 'ROLE_TALENT');
        $scout = new Group('Scout', 'ROLE_SCOUT');
        $admin = new Group('Admin', 'ROLE_ADMIN');

        $manager->persist($talent);
        $manager->persist($scout);
        $manager->persist($admin);

        $manager->flush();
    }
}