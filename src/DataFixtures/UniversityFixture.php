<?php

namespace App\DataFixtures;

use App\Entity\University;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UniversityFixture extends Fixture
{

    public function __construct()
    {
    }

    public function load(ObjectManager $manager)
    {
        $university = new University();
        $university->setTitle('Hofstra');

        $manager->persist($university);

        $manager->flush();
    }
}
