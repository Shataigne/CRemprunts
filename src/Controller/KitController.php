<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\EmpruntMateriel;
use App\Entity\Materiel;
use App\Form\SearchFormType;
use App\Repository\CentreRepository;
use App\Repository\MaterielRepository;
use App\Service\CalendrierService;
use App\Service\EmpruntService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/kit', name: 'app_kit')]
class KitController extends AbstractController
{

    #[Route('/catalogue', name: '_catalogue')]
    public function index(MaterielRepository $materielRepository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);
        $kits = $materielRepository->findByFilters($data, "Kit de Simulation");


        return $this->render('kit/kit_catalogue.html.twig', [
            'kits'=>$kits,
            'form' => $form
        ]);
    }

    #[Route('/details/{id}', name: '_details')]
    public function details(CentreRepository $centreRepository, Request $request, EntityManagerInterface $entityManager, Materiel $kit): Response
    {
        $emprunt = new EmpruntMateriel();
        $materiel = new Materiel;
        $emprunt ->setEmprunteur($this->getUser());
        $emprunt ->setMateriel($kit);
        $action = $request->query->get('action');
        if (isset($action)) {

            $empruntService = New EmpruntService();
            $date = $empruntService->choixEmprunt($request,$action);
            $allDay = $empruntService->getAllDay();
            $listEmprunt = $materiel->getEmpruntMateriels();

            if ($empruntService->verificationDate($date[0], $date[1], $listEmprunt)) {
                $empruntService->setEmprunt($emprunt,$date,$request,$allDay);
                $entityManager->persist($emprunt);
                $entityManager->flush();
                $this->addFlash('success', 'Reservation effectuée !');
            } else {
                $this->addFlash('error', 'Dates indisponibles');
            }
            return $this->redirectToRoute('app_kit_details',  ['id' => $kit->getId()]);
        } else {
            $centres = $centreRepository->findAll();
            return $this->render('kit/kit_details.html.twig', [
                'kit' => $kit,
                'centres' => $centres
            ]);
        }
    }

    #[Route('/api/{id}/calendar', name: 'api_calendar', methods: ['GET'])]
    public function calendar(Materiel $kit, CalendrierService $calendrierService): JsonResponse
    {
        $calendarData = $calendrierService->setData($kit->getEmpruntMateriels());

        return new JsonResponse($calendarData);
    }

}
