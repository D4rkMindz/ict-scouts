<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Province;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTalentStatusData.
 */
class LoadProvinceData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $province1 = new Province('Aargau', 'AG');
        $province2 = new Province('Appenzell Innerrhoden', 'AI');
        $province3 = new Province('Appenzell Ausserrhoden', 'AR');
        $province4 = new Province('Bern', 'BE');
        $province5 = new Province('Basel-Landschaft', 'BL');
        $province6 = new Province('Basel-Stadt', 'BS');
        $province7 = new Province('Freiburg', 'FR');
        $province8 = new Province('Genf', 'GE');
        $province9 = new Province('Glarus', 'GL');
        $province10 = new Province('Graubünden', 'GR');
        $province11 = new Province('Jura', 'JU');
        $province12 = new Province('Luzern', 'LU');
        $province13 = new Province('Neuenburg', 'NE');
        $province14 = new Province('Nidwalden', 'NW');
        $province15 = new Province('Obwalden', 'OW');
        $province16 = new Province('St. Gallen', 'SG');
        $province17 = new Province('Schaffhausen', 'SH');
        $province18 = new Province('Solothurn', 'SO');
        $province19 = new Province('Schwyz', 'SZ');
        $province20 = new Province('Thurgau', 'TG');
        $province21 = new Province('Tessin', 'TI');
        $province22 = new Province('Uri', 'UR');
        $province23 = new Province('Waadt', 'VD');
        $province24 = new Province('Wallis', 'VS');
        $province25 = new Province('Zug', 'ZG');
        $province26 = new Province('Zürich', 'ZH');

        $manager->persist($province1);
        $manager->persist($province2);
        $manager->persist($province3);
        $manager->persist($province4);
        $manager->persist($province5);
        $manager->persist($province6);
        $manager->persist($province7);
        $manager->persist($province8);
        $manager->persist($province9);
        $manager->persist($province10);
        $manager->persist($province11);
        $manager->persist($province12);
        $manager->persist($province13);
        $manager->persist($province14);
        $manager->persist($province15);
        $manager->persist($province16);
        $manager->persist($province17);
        $manager->persist($province18);
        $manager->persist($province19);
        $manager->persist($province20);
        $manager->persist($province21);
        $manager->persist($province22);
        $manager->persist($province23);
        $manager->persist($province24);
        $manager->persist($province25);
        $manager->persist($province26);

        $manager->flush();
    }
}
