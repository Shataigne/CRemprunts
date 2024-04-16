<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\PasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/mon_profil', name: 'app_profil')]
    public function monProfil(): Response
    {
        return $this->render('profil/monProfil.html.twig');
    }

    #[Route('/nouveau_motDePasse/{id}', name: 'app_new_mdp')]
    public function NouveauMotDePasse(Utilisateur $utilisateur, Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, ): Response
    {
        $form=$this->createForm(PasswordFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if ($userPasswordHasher->isPasswordValid($utilisateur,$form->get('lastPassword')->getData())) {
                if ( $form->get('newPassword')->getData() ==  $form->get('verification')->getData()) {
                    $newPassword = $form->get('newPassword')->getData();
                    $utilisateur->setPassword(
                        $userPasswordHasher->hashPassword(
                            $utilisateur,
                            $newPassword));
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_profil');

                } else {
                    $this->addFlash('error','Le nouveau mot de passe ne correspond  pas.');
                }

            } else {
                $this->addFlash('error','Mot de passe incorrect');
            }
            return $this->redirectToRoute('app_new_mdp',  ['id' => $utilisateur->getId()]);
        }else {
        return $this->render('profil/modificationMotDePasse.html.twig', [
            'form' => $form,

        ]);
        }
    }

}


