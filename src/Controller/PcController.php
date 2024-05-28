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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pc', name: 'app_pc')]
class PcController extends AbstractController
{

    #[Route('/catalogue', name: '_catalogue')]
    public function index(MaterielRepository $materielRepository, Request $request): Response
    {

        $sdata = new SearchData();
        $form = $this->createForm(SearchFormType::class, $sdata, [
            'defaultCentre' => $this->getUser()->getCentre(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sdata = $form->getData();
        } else {
            $sdata->setCentres($this->getUser()->getCentre());

        }

        $pcs = $materielRepository->findByFilters($sdata, "ordinateur");


        return $this->render('pc/pc_catalogue.html.twig', [
            'pcs'=> $pcs,
            'form' => $form
        ]);
    }

    #[Route('/details/{id}', name: '_details', requirements: ['id' => '\d+'])]
    public function details(CentreRepository $centreRepository,
                            Request $request,
                            EntityManagerInterface $entityManager,
                            Materiel $pc, MailerInterface $mailer): Response
    {
        $emprunt = new EmpruntMateriel();
        $materiel = new Materiel;
        $emprunt ->setEmprunteur($this->getUser());
        $emprunt ->setMateriel($pc);
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
                $nom = $pc->getLibelle();
                $empruntService->envoieMail($this->getUser(),$emprunt,$nom,$mailer);
            } else {
                $this->addFlash('error', 'Dates indisponibles');
            }
            return $this->redirectToRoute('app_pc_details',  ['id' => $pc->getId()]);
        } else {

            return $this->render('pc/pc_details.html.twig', [
                'pc' => $pc
            ]);
        }
    }

    #[Route('/api/{id}/calendar', name: 'api_calendar', methods: ['GET'])]
    public function calendar(Materiel $pc, CalendrierService $calendrierService): JsonResponse
    {

        $calendarData = $calendrierService->setData($pc->getEmpruntMateriels());
        return new JsonResponse($calendarData);
    }

}
