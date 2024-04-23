<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use App\Repository\CentreRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    private CentreRepository $centreRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(CentreRepository $centreRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->centreRepository = $centreRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager,): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $roles =['ROLE_SALLES','ROLE_VEHICULES', 'ROLE_KITS','ROLE_PC', 'ROLE_FLOTTES','ROLE_CENTRES'];
        $centres = $this->centreRepository->findAll();

        for ($i = 0; $i<4; $i++) {

            $user = new Utilisateur();

            $user->setRoles(['ROLE_ADMIN']);
            $user = $this->setUser($user, $faker, $centres);

            $manager->persist($user);
        }

        for ($i = 0; $i<6; $i++) {

            $user = new Utilisateur();

            $user->setRoles(['ROLE_LOUEUR']);
            $user = $this->setUser($user, $faker, $centres);

            $manager->persist($user);
        }

        for ($i = 0; $i<10; $i++) {

            $user = new Utilisateur();
            $roleR = rand(0, count($roles)-1);
            $user->setRoles([$roles[$roleR]]);
            $user = $this->setUser($user, $faker, $centres);

            $manager->persist($user);
        }


        $manager->flush();
    }


    private function setUser($user, $faker,$centres)
    {
        $centreR = rand(0, count($centres)-1);

        $user->setEmail($faker->email);
        $user->setNom($faker->lastName);
        $user->setPrenom($faker->firstName);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'motdepasse'));
        $user->setCentre($centres[$centreR]);
        $user->setPoste($faker->jobTitle);

        return $user;
    }

    public function getDependencies(): array
    {
        return array(
            CentreFixtures::class,
        );
    }

}
