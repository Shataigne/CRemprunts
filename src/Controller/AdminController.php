<?php

namespace App\Controller;


use App\Entity\Materiel;
use App\Entity\Salle;
use App\Entity\Utilisateur;
use App\Entity\Vehicule;
use App\Form\CreateMaterielFormType;
use App\Form\CreateSallesFormType;
use App\Form\CreateVehiculeType;
use App\Form\profilModificationFormType;
use App\Repository\CategorieRepository;
use App\Repository\MaterielRepository;
use App\Repository\SalleRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin', name: 'app_admin')]
class AdminController extends AbstractController
{
    #[Route('/vehicule', name: '_vehicules')]
    public function gestionVehicules(VehiculeRepository $vehiculeRepository): Response
    {
        $vehicules = $vehiculeRepository->findAll();

        return $this->render('admin/vehi_list.html.twig', [
            'vehicules' => $vehicules
        ]);
    }

    #[Route('/vehicule/nouveau', name: '_vehicules_create')]
    public function createVehicule(EntityManagerInterface $entityManager, Request $request): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(CreateVehiculeType::class, $vehicule);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vehicule);
            $entityManager->flush();
            $this->addFlash('succes', 'Véhicule ajouté avec succès');
            return $this->redirectToRoute('app_admin_vehicules');
        }

        return $this->render('admin/vehi_create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/vehicule/modifier/{id}', name: '_vehicules_modifier')]
    public function modifierVehicule(Vehicule $vehicule, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CreateVehiculeType::class, $vehicule);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('succes', 'Véhicule modifié avec succès');
            return $this->redirectToRoute('app_admin_vehicules');
        }

        return $this->render('admin/vehi_modification.html.twig', [
            'form' => $form,
            'vehicule' => $vehicule
        ]);
    }

    #[Route('/vehicule/supprimer/{id}', name: '_vehicules_supprimer')]
    public function supprimerVehicule(Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($vehicule);
        $entityManager->flush();
        $entityManager->flush();$this->addFlash('succes', 'Vehicule supprimé avec succès');
        return $this->redirectToRoute('app_admin_vehicules');
    }


    #[Route('/salles', name: '_salles')]
    public function gestionSalles(SalleRepository $salleRepository): Response
    {
        $salles = $salleRepository->findAll();

        return $this->render('admin/sal_list.html.twig', [
            'salles' => $salles
        ]);
    }

    #[Route('/salles/nouveau', name: '_salles_create')]
    public function createSalle(EntityManagerInterface $entityManager, Request $request): Response
    {

        $salle = new Salle();

        $form = $this->createForm(CreateSallesFormType::class, $salle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salle);
            $entityManager->flush();
            $this->addFlash('succes', 'salle ajoutée avec succès');
            return $this->redirectToRoute('app_admin_salles');

        }

        return $this->render('admin/sal_create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/salles/modifier/{id}', name: '_salles_modifier')]
    public function modifierSalle(Salle $salle, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CreateSallesFormType::class, $salle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('succes', 'salle modifiée avec succès');
            return $this->redirectToRoute('app_admin_salles');

        }

        return $this->render('admin/sal_modification.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/salles/supprimer/{id}', name: '_salles_supprimer')]
    public function supprimerSalle(Salle $salle, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($salle);
        $entityManager->flush();
        $this->addFlash('succes', ' salle supprimée avec succès');
        return $this->redirectToRoute('app_admin_salles');
    }


    #[Route('/materiel/{categorie}', name: '_materiel')]
    public function gestionMateriel(MaterielRepository $materielRepository, Request $request, CategorieRepository $categorieRepository): Response
    {
        $libelle = $request->attributes->get('categorie');
        $categorie = $categorieRepository->findOneBy(["libelle" => $libelle]);
        $materiels = $materielRepository->findBy(["categorie" => $categorie->getId()]);

        return $this->render('admin/mat_list.html.twig', [
            'materiels' => $materiels,
            'categorie' => $libelle
        ]);
    }

    #[Route('/materiel/{categorie}/nouveau', name: '_materiel_create')]
    public function createMateriel(EntityManagerInterface $entityManager, Request $request, CategorieRepository $categorieRepository): Response
    {

        $materiel = new Materiel();
        $libelle = $request->attributes->get('categorie');
        $categorie = $categorieRepository->findOneBy(["libelle" => $libelle]);

        $form = $this->createForm(CreateMaterielFormType::class, $materiel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $materiel->setCategorie($categorie);
            $entityManager->persist($materiel);
            $entityManager->flush();
            $this->addFlash('succes', $libelle.' ajouté avec succès');
            return $this->redirectToRoute('app_admin_materiel', ["categorie" => $libelle]);
        }

        return $this->render('admin/mat_create.html.twig', [
            'form' => $form,
            'categorie' => $libelle
        ]);
    }

    #[Route('/materiel/{categorie}/modifier/{id}', name: '_materiel_modifier')]
    public function modifierMateriel(Materiel $materiel, EntityManagerInterface $entityManager, Request $request): Response
    {
        $libelle = $request->attributes->get('categorie');
        $form = $this->createForm(CreateMaterielFormType::class, $materiel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('succes', $libelle . ' modifié avec succès');
            return $this->redirectToRoute('app_admin_materiel', ["categorie" => $libelle]);
        }

        return $this->render('admin/mat_modification.html.twig', [
            'form' => $form,
            'categorie' => $libelle
        ]);
    }

    #[Route('/materiel/{categorie}/supprimer/{id}', name: '_materiel_supprimer')]
    public function supprimerMateriel(Materiel $materiel, EntityManagerInterface $entityManager, Request $request): Response
    {
        $libelle = $request->attributes->get('categorie');
        $entityManager->remove($materiel);
        $entityManager->flush();$this->addFlash('succes', $libelle . ' supprimé avec succès');
        return $this->redirectToRoute('app_admin_materiel', ["categorie" => $libelle]);
    }


    #[Route('/profil', name: '_profil')]
    public function gestionUtilisateurs(UtilisateurRepository $utilisateurRepository): Response
    {
        $users = $utilisateurRepository->findAll();

        return $this->render('admin/ut_list.html.twig', [
            'users' => $users
        ]);
    }
    #[Route(path: '/profil/modification/{id}', name: '_profil_modification')]
    public function ModifierProfil(Request $request, EntityManagerInterface $entityManager, Utilisateur $user): Response
    {
        $form = $this->createForm(ProfilModificationFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush($user);
            $this->addFlash('succes','Profil modifié avec succès');
            return $this->redirectToRoute('app_admin_profil');

        }
        return $this->render('admin/ut_modification.html.twig', ['form' => $form]);
    }

    #[Route('/profil/supprimer/{id}', name: '_profil_supprimer')]
    public function supprimerProfil(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('succes', ' Utilisateur supprimé avec succès');
        return $this->redirectToRoute('app_admin_profil');
    }


}
