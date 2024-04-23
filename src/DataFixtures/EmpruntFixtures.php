<?php

namespace App\DataFixtures;


use App\Entity\EmpruntMateriel;
use App\Entity\EmpruntSalle;
use App\Entity\EmpruntVehicule;
use App\Repository\MaterielRepository;
use App\Repository\SalleRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VehiculeRepository;
use DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;


class EmpruntFixtures extends Fixture implements DependentFixtureInterface
{
    private SalleRepository $salleRepository;
    private VehiculeRepository $vehiculeRepository;
    private MaterielRepository $materielRepository;
    private UtilisateurRepository $utilisateurRepository;

    public function __construct(SalleRepository $salleRepository, VehiculeRepository $vehiculeRepository, MaterielRepository $materielRepository, UtilisateurRepository $utilisateurRepository)
    {
        $this->salleRepository = $salleRepository;
        $this->vehiculeRepository = $vehiculeRepository;
        $this->materielRepository = $materielRepository;
        $this->utilisateurRepository = $utilisateurRepository;
    }

    public function setLoadOrder(): void
    {
        $this->loadOrder = 2; // Définir l'ordre de chargement sur 2 (les fixtures avec un ordre de chargement inférieur seront chargées en premier)
    }


    public function load(ObjectManager $manager,): void
    {

        $users = $this->utilisateurRepository->findAll();
        $vehicules = $this->vehiculeRepository->findAll();
        $salles = $this->salleRepository->findAll();
        $materiels = $this->materielRepository->findAll();

        for ($i = 0; $i<20; $i++) {

            $vehiculeR = rand(0, count($vehicules)-1);
            $userR = rand(0, count($users)-1);
            $empruntVehi = new EmpruntVehicule();

            $empruntVehi->setVehicule($vehicules[$vehiculeR]);
            $empruntVehi->setEmprunteur($users[$userR]);
            $empruntVehi = $this->setEmprunt($empruntVehi, $empruntVehi->getVehicule());

            $manager->persist($empruntVehi);
        }

        for ($i = 0; $i<20; $i++) {

            $salleR = rand(0, count($vehicules)-1);
            $userR = rand(0, count($users)-1);
            $empruntSalle = new EmpruntSalle();

            $empruntSalle->setSalle($salles[$salleR]);
            $empruntSalle->setEmprunteur($users[$userR]);
            $empruntSalle = $this->setEmprunt($empruntSalle, $empruntSalle->getSalle());

            $manager->persist($empruntSalle);
        }


        for ($i = 0; $i<20; $i++) {

            $materielR = rand(0, count($materiels)-1);
            $userR = rand(0, count($users)-1);
            $empruntMat = new EmpruntMateriel();

            $empruntMat->setMateriel($materiels[$materielR]);
            $empruntMat->setEmprunteur($users[$userR]);
            $empruntMat = $this->setEmprunt($empruntMat, $empruntMat->getMateriel());

            $manager->persist($empruntMat);
        }


        $manager->flush();


    }

    private function setEmprunt($emprunt, $objet)
    {
        $faker = Faker\Factory::create('fr_FR');

        $emprunt->setAllDay($faker->boolean());
        $emprunt->setDateDebut($faker->dateTimeBetween('-1 week', '+2 week'));
        $dateFin = clone $emprunt->getDateDebut();;
        if (!$emprunt->isAllDay()){
            $emprunt->setDateFin($dateFin->modify('+4 hours'));
        }else {
            $emprunt->setDateFin($dateFin->modify('+9 hours'));
        }


        $emprunt->setLibelle($emprunt->getEmprunteur()->getPrenom().' intervention '.$objet->getCentre()->getLibelle());

        return $emprunt;

    }


    public function getDependencies(): array
    {
       return array(
           UtilisateurFixtures::class,
           SalleFixtures::class,
           VehiculeFixtures::class,
           MaterielFixtures::class,
       );
    }
}
