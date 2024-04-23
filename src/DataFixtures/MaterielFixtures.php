<?php

namespace App\DataFixtures;


use App\Entity\Materiel;
use App\Entity\Vehicule;
use App\Repository\CategorieRepository;
use App\Repository\CentreRepository;
use App\Repository\MarqueRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class MaterielFixtures extends Fixture implements DependentFixtureInterface
{
    private CentreRepository $centreRepository;
    private MarqueRepository $marqueRepository;
    private CategorieRepository $categorieRepository;

    public function __construct(CentreRepository $centreRepository,MarqueRepository $marqueRepository, CategorieRepository $categorieRepository)
    {
        $this->centreRepository = $centreRepository;
        $this->marqueRepository = $marqueRepository;
        $this->categorieRepository = $categorieRepository;
    }

    public function load(ObjectManager $manager,): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $centres = $this->centreRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        $marques = $this->marqueRepository->findBy(['type' => 'INFO']);

        for ($i = 0; $i<30; $i++) {

            $centreR = rand(0, count($centres)-1);
            $marqueR = rand(0, count($marques)-1);
            $categorieR = rand(0, count($categories)-1);
            $materiel = new Materiel();

            $materiel->setCategorie($categories[$categorieR]);
            $materiel->setCentre($centres[$centreR]);
            $materiel->setMarque($marques[$marqueR]);
            $materiel->setModele($faker->word());
            $materiel->setNoSerie($faker->numerify('materiel-####'));
            $materiel->setLibelle($materiel->getMarque()->getLibelle().' '.$materiel->getModele().' '.$materiel->getNoSerie());

            $manager->persist($materiel);
        }

        $manager->flush();


    }

    public function getDependencies(): array
    {
        return array(
            CentreFixtures::class,
            MarqueFixtures::class,
            CategorieFixtures::class,
        );
    }

}
