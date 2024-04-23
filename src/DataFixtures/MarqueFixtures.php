<?php

namespace App\DataFixtures;


use App\Entity\Marque;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class MarqueFixtures extends Fixture
{

    public function __construct()
    {

    }

    public function load(ObjectManager $manager,): void
    {

        $this->setMarqueInfo('SAMSUNG',$manager);
        $this->setMarqueInfo('LENOVO',$manager);
        $this->setMarqueInfo('DELL',$manager);
        $this->setMarqueVehi('CITROEN',$manager);
        $this->setMarqueVehi('PEUGEOT',$manager);
        $this->setMarqueVehi('DACIA',$manager);

        $manager->flush();
    }

    private function setMarqueVehi($libelle, $manager): void
    {

        $marque = new Marque();
        $marque->setLibelle($libelle);
        $marque->setType('VEHI');


        $manager->persist($marque);

    }

    private function setMarqueInfo($libelle, $manager): void
    {

        $marque = new Marque();
        $marque->setLibelle($libelle);
        $marque->setType('INFO');


        $manager->persist($marque);

    }

}
