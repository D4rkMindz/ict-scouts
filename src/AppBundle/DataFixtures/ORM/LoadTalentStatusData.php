<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TalentStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTalentStatusData.
 */
class LoadTalentStatusData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $active = new TalentStatus('Aktiv');
        $inactive = new TalentStatus('Inaktiv');
        $former = new TalentStatus('Ehemalig');

        $manager->persist($active);
        $manager->persist($inactive);
        $manager->persist($former);

        $manager->flush();
    }
}
