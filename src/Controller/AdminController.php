<?php

namespace App\Controller;


use App\Entity\Centre;
use App\Entity\Marque;
use App\Entity\Materiel;
use App\Entity\Salle;
use App\Entity\Utilisateur;
use App\Entity\Vehicule;
use App\Form\CreateCentresFormType;
use App\Form\CreateMaterielFormType;
use App\Form\CreateSallesFormType;
use App\Form\CreateVehiculeType;
use App\Form\GestionRolesFormType;
use App\Form\profilModificationFormType;
use App\Repository\CategorieRepository;
use App\Repository\CentreRepository;
use App\Repository\MarqueRepository;
use App\Repository\MaterielRepository;
use App\Repository\SalleRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function createVehicule(EntityManagerInterface $entityManager, Request $request, MarqueRepository $marqueRepository): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(CreateVehiculeType::class, $vehicule, [
            'marques' => $marqueRepository->findBy([
                'type' => 'VEHI'
            ]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $listEquipements = $form->get('equipements')->getData();
            $arrayEquipements = explode(',', $listEquipements);
            $vehicule->setEquipements($arrayEquipements);
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
    public function modifierVehicule(Vehicule $vehicule, EntityManagerInterface $entityManager, Request $request,MarqueRepository $marqueRepository): Response
    {
        $form = $this->createForm(CreateVehiculeType::class, $vehicule, [
            'marques' => $marqueRepository->findBy([
                'type' => 'VEHI'
            ]),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $listEquipements = $form->get('equipements')->getData();
            $arrayEquipements = explode(',', $listEquipements);
            $vehicule->setEquipements($arrayEquipements);
            $entityManager->persist($vehicule);
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

    #[Route('/vehicule/marque', name: '_vehicules_marque')]
    public function createMarque(EntityManagerInterface $entityManager, Request $request, MarqueRepository $marqueRepository): Response
    {

        $action = $request->query->get('action');

        switch ($action) {
            case 'supprimer':
                $marqueId = $request->query->get('id');
                $marque = $marqueRepository->find($marqueId);
                if ($marque) {
                    $entityManager->remove($marque);
                    $entityManager->flush();
                }
                break;

            case 'ajouter':
                $nom = strtoupper($request->request->get('libelle'));
                if ($nom) {
                    $marque = new Marque();
                    $marque->setLibelle($nom);
                    $marque->setType('VEHI');
                    $entityManager->persist($marque);
                    $entityManager->flush();
                }
                break;
            case 'modifier':
                $marqueId = $request->query->get('id');
                $marque = $marqueRepository->find($marqueId);
                $nouveauNom = strtoupper($request->request->get('nouveau_libelle'));
                if ($marque && $nouveauNom) {
                    $marque->setLibelle($nouveauNom);
                    $entityManager->persist($marque);
                    $entityManager->flush();
                }
                break;
            default:
                $marques = $marqueRepository->findBy(['type' => 'VEHI']);
        }

        if(!empty($marques)){
            return $this->render('admin/vehi_marque.html.twig', ['marques' => $marques, 'type' => 'vehicules']);
        }else{
            return $this->redirectToRoute('app_admin_vehicules_marque');
        }

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
            $listEquipements = $form->get('equipements')->getData();
            $arrayEquipements = explode(',', $listEquipements);
            $salle->setEquipements($arrayEquipements);
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
            $listEquipements = $form->get('equipements')->getData();
            $arrayEquipements = explode(',', $listEquipements);
            $salle->setEquipements($arrayEquipements);
            $entityManager->persist($salle);
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
    public function createMateriel(EntityManagerInterface $entityManager, Request $request, CategorieRepository $categorieRepository, MarqueRepository $marqueRepository): Response
    {

        $materiel = new Materiel();
        $libelle = $request->attributes->get('categorie');
        $categorie = $categorieRepository->findOneBy(["libelle" => $libelle]);

        $form = $this->createForm(CreateMaterielFormType::class, $materiel, [
            'marques' => $marqueRepository->findBy([
                'type' => 'INFO'
            ]),
        ]);
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
    public function modifierMateriel(Materiel $materiel, EntityManagerInterface $entityManager, Request $request, MarqueRepository $marqueRepository): Response
    {
        $libelle = $request->attributes->get('categorie');
        $form = $this->createForm(CreateMaterielFormType::class, $materiel, [
            'marques' => $marqueRepository->findBy([
                'type' => 'INFO'
            ]),
        ]);
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


    #[Route('/materiels/marque', name: '_materiels_marque')]
    public function createMarqueMat(EntityManagerInterface $entityManager, Request $request, MarqueRepository $marqueRepository): Response
    {

        $action = $request->query->get('action');

        switch ($action) {
            case 'supprimer':
                $marqueId = $request->query->get('id');
                $marque = $marqueRepository->find($marqueId);
                if ($marque) {
                    $entityManager->remove($marque);
                    $entityManager->flush();
                }
                break;

            case 'ajouter':
                $nom = strtoupper($request->request->get('libelle'));
                if ($nom) {
                    $marque = new Marque();
                    $marque->setLibelle($nom);
                    $marque->setType('INFO');
                    $entityManager->persist($marque);
                    $entityManager->flush();
                }
                break;
            case 'modifier':
                $marqueId = $request->query->get('id');
                $marque = $marqueRepository->find($marqueId);
                $nouveauNom = strtoupper($request->request->get('nouveau_libelle'));
                if ($marque && $nouveauNom) {
                    $marque->setLibelle($nouveauNom);
                    $entityManager->persist($marque);
                    $entityManager->flush();
                }
                break;
            default:
                $marques = $marqueRepository->findBy(['type' => 'INFO']);
        }

        if(!empty($marques)){
            return $this->render('admin/vehi_marque.html.twig', ['marques' => $marques, 'type' => 'materiels']);
        }else{
            return $this->redirectToRoute('app_admin_materiels_marque');
        }

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
    public function ModifierProfil(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, Utilisateur $user): Response
    {
        $form = $this->createForm(ProfilModificationFormType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('succes','Profil modifié avec succès');
            return $this->redirectToRoute('app_admin_profil');

        }
        return $this->render('admin/ut_modification.html.twig', ['form' => $form]);
    }



    #[Route(path: '/profil/roles/{id}', name: '_profil_roles')]
    public function ModifierRoles( Utilisateur $user, EntityManagerInterface $entityManager, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            $roles = array_merge($roles,['ROLE_LOUEUR', 'ROLE_CENTRES']);
        }
        if (in_array('ROLE_LOUEUR', $roles)) {
            $roles = array_merge($roles,['ROLE_SALLES','ROLE_VEHICULES', 'ROLE_KITS','ROLE_PC', 'ROLE_FLOTTES']);
        }

        $form = $this->createForm(GestionRolesFormType::class,null, ['roles' => $roles,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data['administrateur'] === TRUE) {
                $newroles[] = 'ROLE_ADMIN';
            }else {
                if (($data['salles'] === TRUE
                        && $data['vehicules'] === TRUE
                        && $data['kits'] === TRUE
                        && $data['pc'] === TRUE
                        && $data['flottes'] === TRUE)
                    || $data['emprunteur'] === TRUE) {
                    $newroles[] = 'ROLE_LOUEUR';
                } else {
                    if ($data['salles'] === TRUE)  $newroles[] = 'ROLE_SALLES';
                    if ($data['vehicules'] === TRUE)  $newroles[] = 'ROLE_VEHICULES';
                    if ($data['kits'] === TRUE)  $newroles[] = 'ROLE_KITS';
                    if ($data['pc'] === TRUE)  $newroles[] = 'ROLE_PC';
                    if ($data['flottes'] === TRUE)  $newroles[] = 'ROLE_FLOTTES';
                }
               if($data['centres'] === TRUE)  $newroles[] = 'ROLE_CENTRES' ;

            }
            $user->setRoles($newroles);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_profil');
        }

        return $this->render('admin/ut_roles.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }


    #[Route('/profil/supprimer/{id}', name: '_profil_supprimer')]
    public function supprimerProfil(Utilisateur $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('succes', ' Utilisateur supprimé avec succès');
        return $this->redirectToRoute('app_admin_profil');
    }



    #[Route('/centres', name: '_centres')]
    public function gestionCentres(EntityManagerInterface $entityManager, Request $request, CentreRepository $centreRepository): Response
    {

        $action = $request->query->get('action');

        switch ($action) {
            case 'supprimer':
                $centreId = $request->query->get('id');
                $centre = $centreRepository->find($centreId);
                if ($centre) {
                    $entityManager->remove($centre);
                    $entityManager->flush();
                }
                break;

            case 'ajouter':
                $nom = $request->request->get('nom');
                $numero = $request->request->get('numero');
                $rue = $request->request->get('rue');
                $cpo = $request->request->get('cpo');
                $ville = $request->request->get('ville');
                if ($nom) {
                    $centre = new Centre();
                    $centre->setLibelle($nom);
                    $centre->setNumero($numero);
                    $centre->setrue($rue);
                    $centre->setcpo($cpo);
                    $centre->setville($ville);
                    $entityManager->persist($centre);
                    $entityManager->flush();
                }
                break;
            default:
                $centres = $centreRepository->findAll();
        }

        if(!empty($centres)){
            return $this->render('admin/centres.html.twig', ['centres' => $centres]);
        }else{
            return $this->redirectToRoute('app_admin_centres');
        }

    }

    #[Route('/centres/modifier/{id}', name: '_centres_modifier')]
    public function modifierCentre(Centre $centre, EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(CreateCentresFormType::class, $centre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($centre);
            $entityManager->flush();
            $this->addFlash('succes', 'centre modifiée avec succès');
            return $this->redirectToRoute('app_admin_centres');

        }

        return $this->render('admin/centre_modification.html.twig', [
            'form' => $form,
            'centre' => $centre,
        ]);
    }



}
