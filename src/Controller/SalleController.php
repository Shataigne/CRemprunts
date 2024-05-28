<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\EmpruntSalle;
use App\Entity\Salle;
use App\Form\SearchFormType;
use App\Repository\CentreRepository;
use App\Repository\SalleRepository;
use App\Service\CalendrierService;
use App\Service\EmpruntService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use function Sodium\add;
use function Symfony\Component\Clock\now;

#[Route('/salle', name: 'app_salles')]
class SalleController extends AbstractController
{

    // Salle de cours
    #[Route('/cours', name: '_cours_catalogue')]
    public function listSalleCours(SalleRepository $salleRepository, Request $request): Response
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

        $salles = $salleRepository->findByFilters($sdata, "CRS");

        return $this->render('salle/salle_catalogue.html.twig', ["salles"=>$salles, "form"=>$form]);
    }




    //salle de reunion

    #[Route('/reunion', name: '_reunion_catalogue')]
    public function listSalleReunion(SalleRepository $salleRepository, Request $request): Response
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
        $salles = $salleRepository->findByFilters($sdata, "REU");

        return $this->render('salle/salle_catalogue.html.twig', ["salles"=>$salles, "form"=>$form]);
    }

    #[Route('/details/{id}', name: '_details')]
    public function details(CentreRepository $centreRepository, Request $request, EntityManagerInterface $entityManager, Salle $salle, MailerInterface $mailer): Response
    {
        $emprunt = new EmpruntSalle();
        $emprunt ->setEmprunteur($this->getUser());
        $emprunt ->setSalle($salle);
        $action = $request->query->get('action');
        if (isset($action)) {

            $empruntService = New EmpruntService();
            $date = $empruntService->choixEmprunt($request,$action);
            $allDay = $empruntService->getAllDay();
            $listEmprunt = $salle->getEmpruntSalles();

            if ($empruntService->verificationDate($date[0], $date[1], $listEmprunt)) {
                $empruntService->setEmprunt($emprunt,$date,$request,$allDay);
                $entityManager->persist($emprunt);
                $entityManager->flush();
                $this->addFlash('success', 'Reservation effectuée !');

                $nom = 'salle numéro '.$salle->getNumero();
                $empruntService->envoieMail($this->getUser(),$emprunt,$nom,$mailer);
            } else {
                $this->addFlash('error', 'Dates indisponibles');
            }
            return $this->redirectToRoute('app_salles_details', ['id' => $salle->getId()]);
        } else {
            $centres = $centreRepository->findAll();
            return $this->render('salle/salle_details.html.twig', [
                'salle' => $salle,
                'centres' => $centres
            ]);
        }
    }

    #[Route('/planning', name: '_planning')]
    public function planningSalles(SalleRepository $salleRepository, Request $request): Response
    {

        $salles = $salleRepository->findBy(['centre' => $this->getUser()->getCentre()]);
        $now = new \DateTime();

        $debutSemaine = clone $now->modify('Monday this week');
        $finSemaine = clone $now->modify('Sunday this week');

        return $this->render('salle/salle_planning.html.twig', ["salles"=>$salles, 'debutSemaine'=>$debutSemaine->format('l d F Y'), 'finSemaine'=>$finSemaine->format('l d F Y')]);
    }



    #[Route('/api/{id}/calendar', name: 'api_calendar', methods: ['GET'])]
    public function calendar(Salle $salle, CalendrierService $calendrierService): JsonResponse
    {
        $calendarData = $calendrierService->setData($salle->getEmpruntSalles());

        return new JsonResponse($calendarData);
    }

}
