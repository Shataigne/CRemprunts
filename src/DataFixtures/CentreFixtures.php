<?php

namespace App\DataFixtures;

use App\Entity\Centre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class CentreFixtures extends Fixture
{

    public function __construct()
    {

    }

    public function load(ObjectManager $manager,): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i<10; $i++) {

            $centre = new Centre();

            $centre->setLibelle('Centre de formation '.$i);
            $centre->setNumero($faker->numberBetween(0, 40));
            $centre->setRue($faker->streetName());
            $centre->setCpo($faker->postcode());
            $centre->setVille($faker->city());

            $manager->persist($centre);
        }

        $manager->flush();
    }

}
