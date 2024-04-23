<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class CategorieFixtures extends Fixture
{

    public function __construct()
    {

    }

    public function load(ObjectManager $manager,): void
    {

        $this->setCategorie('ordinateur',$manager);
        $this->setCategorie('Flotte de PC',$manager);
        $this->setCategorie('Kit de Simulation',$manager);

        $manager->flush();
    }

    private function setCategorie($libelle, $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $categorie = new Categorie();
        $categorie->setLibelle($libelle);
        $categorie->setDescription($faker->paragraph(2));


        $manager->persist($categorie);

    }

}
