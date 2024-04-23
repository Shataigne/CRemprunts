<?php

namespace App\DataFixtures;


use App\Entity\Vehicule;
use App\Repository\CentreRepository;
use App\Repository\MarqueRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class VehiculeFixtures extends Fixture implements DependentFixtureInterface
{
    private CentreRepository $centreRepository;
    private MarqueRepository $marqueRepository;

    public function __construct(CentreRepository $centreRepository, MarqueRepository $marqueRepository)
    {
        $this->centreRepository = $centreRepository;
        $this->marqueRepository = $marqueRepository;
    }

    public function load(ObjectManager $manager,): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $centres = $this->centreRepository->findAll();
        $marques = $this->marqueRepository->findBy(['type' => 'VEHI']);

        for ($i = 0; $i<20; $i++) {

            $centreR = rand(0, count($centres)-1);
            $marqueR = rand(0, count($marques)-1);
            $vehicule = new Vehicule();

            $vehicule->setCentre($centres[$centreR]);
            $vehicule->setMarque($marques[$marqueR]);
            $vehicule->setModele($faker->word());
            $vehicule->setPlaque( $faker->regexify('[A-Z]{2}-\d{3}-[A-Z]{2}'));
            $vehicule->setEquipements($faker->randomElements(['GPS', 'radar de recul', 'radio', 'climatisation'], 1));
            $vehicule->setPlaces($faker->numberBetween(2, 7));
            $vehicule->setLibelle($vehicule->getMarque()->getLibelle().' '.$vehicule->getModele().' '.$vehicule->getPlaque());


            $manager->persist($vehicule);
        }

        $manager->flush();


    }

    public function getDependencies(): array
    {
        return array(
            CentreFixtures::class,
            MarqueFixtures::class
        );
    }
}
