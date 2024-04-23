<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use App\Repository\CentreRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SalleFixtures extends Fixture implements DependentFixtureInterface
{
    private CentreRepository $centreRepository;

    public function __construct(CentreRepository $centreRepository)
    {
        $this->centreRepository = $centreRepository;
    }

    public function load(ObjectManager $manager,): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $categories = ['CRS', 'REU'];
        $centres = $this->centreRepository->findAll();

        for ($i = 0; $i<20; $i++) {

            $centreR = rand(0, count($centres)-1);
            $categorieR = rand(0, count($categories)-1);
            $salle = new Salle();

            $salle->setCentre($centres[$centreR]);
            $salle->setEquipements($faker->randomElements(['projecteur', 'tableau blanc', 'ordinateur', 'machine à café', 'kit de simulation', 'tableau noir', 'écran'], 3));
            $salle->setCategorie($categories[$categorieR]);
            $salle->setNumero($faker->numberBetween(1, 30));
            $salle->setEtage($faker->numberBetween(-1, 5));
            $salle->setBatiment($faker->randomLetter());
            $salle->setPlaces($faker->numberBetween(5, 40));

            $manager->persist($salle);
        }

        $manager->flush();


    }

    public function getDependencies(): array
    {
        return array(
            CentreFixtures::class,
        );
    }

}
