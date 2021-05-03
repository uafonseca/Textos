<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Finder\Finder;

class GeographyFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $finder->in(__DIR__ . '/Data');
        $finder->name('*.sql');
        $finder->files();
        $finder->sortByName();

        foreach( $finder as $file ){
            print "Importing: {$file->getBasename()} " . PHP_EOL;

            $sql = $file->getContents();

            $manager->getConnection()->exec($sql);  // Execute native SQL

            $manager->flush();
        }

        $manager->flush();
    }
}
