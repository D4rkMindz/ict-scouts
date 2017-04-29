<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Zip;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use League\Csv\Reader;

/**
 * Class LoadZipData.
 *
 * @see https://www.post.ch/de/geschaeftlich/themen-a-z/adressen-pflegen-und-geodaten-nutzen/adress-und-geodaten
 */
class LoadZipData extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $provRepo = $manager->getRepository('AppBundle:Province');
        $zipRepo = $manager->getRepository('AppBundle:Zip');

        try {
            $csvReader = Reader::createFromPath(__DIR__.'/../../Resources/data/fixtures/Post_Adressdaten.csv');
        } catch (\Exception $e) {
            throw $e;
        }
        $csvReader->setDelimiter(';');
        $csvReader->addFilter(function($row) use ($provRepo, $zipRepo, $manager) {
            switch ($row[0]) {
                case '0':
                    // Info table
                    break;
                case '1':
                    // Zip-Codes
                    $existingZip = $zipRepo->find($row[1]);
                    if ($existingZip) { // Update existing zip
                        $existingZip->setZip($row[4]);
                        $existingZip->setCity($row[7]);
                        if ($existingZip->getProvince()->getNameShort() !== $row[9]) { // Province of zip changed.
                            $existingZip->getProvince()->removeZip($existingZip);
                            $prov = $provRepo->findOneBy(['nameShort' => $row[9]]);
                            if ($prov) { // Change Province if still in Switzerland.
                                $prov->addZip($existingZip);
                                $manager->persist($prov);
                                $manager->persist($existingZip->getProvince());
                            } else {
                                $manager->remove($existingZip);
                            }
                        }
                        $manager->persist($existingZip);
                    } else { // Create new zip.
                        $zip = new Zip($row[1], $row[4], $row[7]);
                        $prov = $provRepo->findOneBy(['nameShort' => $row[9]]);
                        if ($prov) { // Set province, if zip is in Switzerland.
                            $prov->addZip($zip);
                            $manager->persist($prov);
                        }
                    }

                    return true;
                    break;
                case '2':
                    // Alternative names for zips
                    break;
                case '3':
                    // Political villages
                    break;
                case '4':
                    // Streets
                    break;
                case '5':
                    // Alternative names for streets
                    break;
                case '6':
                    // Building
                    break;
                case '7':
                    // Alternative names for buildings
                    break;
                case '8':
                    // Postman information for delivery
                    break;
                case '12':
                    // Join table between building and political villages
                    break;
            }

            return false;
        });
        $manager->flush();

        $csvReader->fetchAll();
    }
}
