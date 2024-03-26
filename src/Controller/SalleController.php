<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\EmpruntMateriel;
use App\Entity\EmpruntSalle;
use App\Entity\Materiel;
use App\Entity\Salle;
use App\Form\SearchFormType;
use App\Repository\EmpruntMaterielRepository;
use App\Repository\EmpruntSalleRepository;
use App\Repository\SalleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/salles', name: 'app_salles')]
class SalleController extends AbstractController
{

    // Salle de cours
    #[Route('/cours', name: '_cours')]
    public function listSalleCours(SalleRepository $salleRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $salles = $salleRepository->findByFilters($data, "CRS");

        return $this->render('salle/cours_catalogue.html.twig', ["salles"=>$salles, "form"=>$form]);
    }




    //salle de reunion

    #[Route('/reunion', name: '_reunion')]
    public function listSalleReunion(SalleRepository $salleRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $salles = $salleRepository->findByFilters($data, "REU");

        return $this->render('salle/cours_catalogue.html.twig', ["salles"=>$salles, "form"=>$form]);
    }

    #[Route('/details/{id}', name: '_details')]
    public function details(EmpruntSalleRepository $empruntSalleRepository, Request $request, EntityManagerInterface $entityManager, Salle $salle): Response
    {
        $emprunt = new EmpruntSalle();
        $emprunt ->setEmprunteur($this->getUser());
        $emprunt ->setSalle($salle);
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
        return $this->render('salle/salle_details.html.twig', [
            'salle' => $salle
        ]);
    }
}
