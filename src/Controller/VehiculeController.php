<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\EmpruntVehicule;
use App\Entity\Vehicule;
use App\Form\SearchFormType;
use App\Repository\CentreRepository;
use App\Repository\VehiculeRepository;
use App\Service\CalendrierService;
use App\Service\EmpruntService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;


#[Route('/vehicules', name: 'app_vehicule')]

class VehiculeController extends AbstractController
{

    #[Route('/catalogue', name: '_catalogue')]
    public function index(VehiculeRepository $vehiculeRepository, Request $request): Response
    {

        $sdata = new SearchData();
        $form = $this->createForm(SearchFormType::class, $sdata);
        $form->handleRequest($request);

        $vehicules = $vehiculeRepository->findByFilters($sdata);

        return $this->render('vehicule/vehi_catalogue.html.twig', [
            'vehicules' => $vehicules,
            'form' => $form
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/details/{id}', name: '_details')]
    public function details(CentreRepository $centreRepository, Request $request, EntityManagerInterface $entityManager, Vehicule $vehicule): Response
    {
        $emprunt = new EmpruntVehicule();
        $emprunt->setEmprunteur($this->getUser());
        $emprunt->setVehicule($vehicule);
        $action = $request->query->get('action');
        if (isset($action)) {

            $empruntService = New EmpruntService();
            $date = $empruntService->choixEmprunt($request,$action);
            $allDay = $empruntService->getAllDay();
            $listEmprunt = $vehicule->getEmpruntVehicules();

            if ($empruntService->verificationDate($date[0], $date[1], $listEmprunt)) {

                $validator = Validation::createValidator();
                $errors = $validator->validate([$date[0], $date[1]], [
                    new Assert\Expression("value[1] > value[0]"),
                ]);

                if (count($errors) > 0) {
                    $this->addFlash(type:'error', message:"La date de fin doit être postérieure à la date de début.");
                }else {
                    $empruntService->setEmprunt($emprunt,$date,$request,$allDay);
                    $entityManager->persist($emprunt);
                    $entityManager->flush();
                    $this->addFlash('success', 'Reservation effectuée !');
                };

            } else {
                $this->addFlash('error', 'Dates indisponibles');
            }
            return $this->redirectToRoute('app_vehicule_details', ['id' => $vehicule->getId()]);
        } else {
            $centres = $centreRepository->findAll();
            return $this->render('vehicule/vehi_details.html.twig', [
                'vehicule' => $vehicule,
                'centres' => $centres
            ]);
        }
    }

    #[Route('/api/{id}/calendar', name: 'api_calendar', methods: ['GET'])]
    public function calendar(Vehicule $vehicule, CalendrierService $calendrierService): JsonResponse
    {
        $calendarData = $calendrierService->setData($vehicule->getEmpruntVehicules());

        return new JsonResponse($calendarData);
    }

}
