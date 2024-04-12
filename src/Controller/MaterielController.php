<?php

namespace App\Controller;


use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/materiels', name: 'app_materiels')]
class MaterielController extends AbstractController
{

    #[Route('/catalogue', name: '_catalogue')]
    public function listMateriels(MaterielRepository $materielRepository): Response
    {
        $materiels = $materielRepository->findAll();

        return $this->render('materiel/mat_catalogue.html.twig', ["materiels"=>$materiels]);
    }


}
