<?php

namespace App\Service;

use DateInterval;
use DateTime;

class EmpruntService
{
    private bool $allDay = false;
    public function choixEmprunt($request,$action): array
    {
        $allDay =  $this->getAllDay();
        switch ($action) {
            case 'heure':
                $dateDebut = new DateTime($request->request->get('h_date'));
                $dateFin = new DateTime($request->request->get('h_date'));
                $heureDebut = DateInterval::createFromDateString($request->request->get('h_heureDebut'));
                $heureFin = DateInterval::createFromDateString($request->request->get('h_heureFin'));
                $allDay = false;

                break;
            case 'jour':
                $dateDebut = new DateTime($request->request->get('j_date'));
                $dateFin = new DateTime($request->request->get('j_date'));
                $heureDebut = DateInterval::createFromDateString('+7 hours');
                $heureFin = DateInterval::createFromDateString('+18 hours');
                $allDay = true;
                break;
            case 'long':
                $dateDebut = new DateTime($request->request->get('l_dateDebut'));
                $dateFin = new DateTime($request->request->get('l_dateFin'));
                $heureDebut = DateInterval::createFromDateString('+7 hours');
                $heureFin = DateInterval::createFromDateString('+18 hours');
                $allDay = true;
                break;

        }

        $dateDebut->add($heureDebut);
        $dateFin->add($heureFin);
        $this->setAllDay($allDay);

        return [$dateDebut,$dateFin];
    }

    public function setEmprunt($emprunt, $date, $request, bool $allDay) {
        $emprunt->setDateDebut($date[0]);
        $emprunt->setDateFin($date[1]);
        $emprunt->setLibelle($request->request->get('libelle'));
        $emprunt->setDescription($request->request->get('description'));
        $emprunt->setAllDay($allDay);

        return $emprunt;
    }


    public function getAllDay(): bool
    {
        return $this->allDay;
    }

    public function setAllDay($allday): void
    {
        $this->allDay = $allday;
    }

    public function verificationDate(\DateTime $dateDebutVoulu,
                                     \DateTime $dateFinVoulu,
                                               $listEmprunt): bool
    {
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