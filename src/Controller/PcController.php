<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\EmpruntMateriel;
use App\Entity\Materiel;
use App\Form\SearchFormType;
use App\Repository\EmpruntMaterielRepository;
use App\Repository\MaterielRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pc', name: 'app_pc')]
class PcController extends AbstractController
{

    #[Route('/catalogue', name: '_catalogue')]
    public function index(MaterielRepository $materielRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $pcs = $materielRepository->findByFilters($data, "ordinateur");


        return $this->render('pc/pc_catalogue.html.twig', [
            'pcs'=> $pcs,
            'form' => $form
        ]);
    }

    #[Route('/details/{id}', name: '_details')]
    public function details(EmpruntMaterielRepository $empruntMaterielRepository, Request $request, EntityManagerInterface $entityManager, Materiel $pc): Response
    {
        $emprunt = new EmpruntMateriel();
        $emprunt ->setEmprunteur($this->getUser());
        $emprunt ->setMateriel($pc);
        $action = $request->query->get('action');
        $heureDebut = \DateTime::createFromFormat('H:i:s', '7:00:00');
        $heureFin = \DateTime::createFromFormat('H:i:s', '18:00:00');

        switch ($action) {
            case 'heure':
                $dateDebut = new DateTime($request->request->get('h_date'));
                $heureDebut = new DateTime($request->request->get('h_heureDebut'));
                $heureFin = new DateTime($request->request->get('h_heureFin'));
                $emprunt ->setDateDebut($dateDebut);
                $emprunt ->setHeureDebut($heureDebut);
                $emprunt ->setHeureFin($heureFin);
                $emprunt ->setDateFin($dateDebut);
                $entityManager->persist($emprunt);
                $entityManager->flush();

                break;
            case 'jour':
                $dateDebut = new DateTime($request->request->get('j_date'));
                $emprunt ->setDateDebut($dateDebut);
                $emprunt ->setHeureDebut($heureDebut);
                $emprunt ->setHeureFin($heureFin);
                $emprunt ->setDateFin($dateDebut);
                $entityManager->persist($emprunt);
                $entityManager->flush();

                break;
            case 'long':
                $dateDebut = new DateTime($request->request->get('l_dateDebut'));
                $dateFin = new DateTime($request->request->get('l_dateFin'));
                $emprunt ->setDateDebut($dateDebut);
                $emprunt ->setHeureDebut($heureDebut);
                $emprunt ->setHeureFin($heureFin);
                $emprunt ->setDateFin($dateFin);
                $entityManager->persist($emprunt);
                $entityManager->flush();

                break;

        }
        return $this->render('pc/pc_details.html.twig', [
            'pc' => $pc
        ]);
    }

}
