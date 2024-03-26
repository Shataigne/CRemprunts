<?php

namespace App\Controller;

use App\Repository\MaterielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/materiels', name: 'app_materiels')]
class MaterielController extends AbstractController
{

    #[Route('/catalogue', name: '_catalogue')]
    public function listMateriels(MaterielRepository $materielRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $materiels = $materielRepository->findAll();

        return $this->render('materiel/mat_catalogue.html.twig', ["materiels"=>$materiels]);
    }

}
