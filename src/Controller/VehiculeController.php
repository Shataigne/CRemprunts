<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\EmpruntVehicule;
use App\Entity\Vehicule;
use App\Form\SearchFormType;
use App\Repository\CentreRepository;
use App\Repository\EmpruntVehiculeRepository;
use App\Repository\VehiculeRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use function Sodium\add;


#[Route('/vehicules', name: 'app_vehicule')]

class VehiculeController extends AbstractController
{

    #[Route('/catalogue', name: '_catalogue')]
    public function index(VehiculeRepository $vehiculeRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);

        $vehicules = $vehiculeRepository->findByFilters($data);


        return $this->render('vehicule/vehi_catalogue.html.twig', [
            'vehicules' => $vehicules,
            'form' => $form
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/details/{id}', name: '_details')]
    public function details( CentreRepository $centreRepository,EmpruntVehiculeRepository $empruntVehiculeRepository, Request $request, EntityManagerInterface $entityManager, Vehicule $vehicule): Response
    {
        $emprunt = new EmpruntVehicule();
        $emprunt->setEmprunteur($this->getUser());
        $emprunt->setVehicule($vehicule);
        $action = $request->query->get('action');
        if (isset($action)) {
            switch ($action) {
                case 'heure':
                    $dateDebut = new DateTime($request->request->get('h_date'));
                    $dateFin = new DateTime($request->request->get('h_date'));
                    $heureDebut = DateInterval::createFromDateString($request->request->get('h_heureDebut'));
                    $heureFin = DateInterval::createFromDateString($request->request->get('h_heureFin'));
                    break;
                case 'jour':
                    $dateDebut = new DateTime($request->request->get('j_date'));
                    $dateFin = new DateTime($request->request->get('j_date'));
                    $heureDebut = DateInterval::createFromDateString('+7 hours');
                    $heureFin = DateInterval::createFromDateString('+18 hours');
                    break;
                case 'long':
                    $dateDebut = new DateTime($request->request->get('l_dateDebut'));
                    $dateFin = new DateTime($request->request->get('l_dateFin'));
                    $heureDebut = DateInterval::createFromDateString('+7 hours');
                    $heureFin = DateInterval::createFromDateString('+18 hours');
                    break;

            }

            $dateDebut->add($heureDebut);
            $dateFin->add($heureFin);
            if ($this->verificationDate($vehicule,$dateDebut,$dateFin)) {
                $emprunt->setDateDebut($dateDebut);
                $emprunt->setDateFin($dateFin);
                $emprunt->setLibelle($request->request->get('libelle'));
                $emprunt->setDescription($request->request->get('description'));
                $entityManager->persist($emprunt);
                $entityManager->flush();
                $this->addFlash('success', 'Reservation effectué !');
            } else {
                $this->addFlash('error', 'Dates indisponibles');
            }
            return $this->redirectToRoute('app_vehicule_details', ['id'=>$vehicule->getId()]);
        } else {
            $centres = $centreRepository->findAll();
            return $this->render('vehicule/vehi_details.html.twig', [
                'vehicule' => $vehicule,
                'centres' => $centres
            ]);
        }
    }





    public function verificationDate(Vehicule  $vehicule,
                                     \DateTime $dateDebutVoulu,
                                     \DateTime $dateFinVoulu): bool
    {
        $listEmprunt = $vehicule->getEmpruntVehicules();
        $disponibilite = true;
        foreach ($listEmprunt as $emprunt) {
            $dateAvant = $emprunt->getDateDebut() > $dateDebutVoulu && $emprunt->getDateDebut() > $dateFinVoulu ;
            $dateApres = $emprunt->getDateFin() < $dateDebutVoulu && $emprunt->getDateFin() < $dateFinVoulu ;

            if (!$dateAvant && !$dateApres) {
                $disponibilite = false;

            }
        }
        return $disponibilite;
    }
}
