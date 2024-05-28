<?php

namespace App\Service;

class CalendrierService
{

    public function setData($listeEmprunts): array
    {
        $calendarData = [];
        foreach ($listeEmprunts as $emprunt) {
            $calendarData[] = [
                'id' => $emprunt->getId(),
                'title' => $emprunt->getDescription() == null || $emprunt->getDescription() == '' ? $emprunt->getLibelle() : $emprunt->getDescription() ,
                'start' => $emprunt->getDateDebut()->format('Y-m-d H:i:s'),
                'end' => $emprunt->getDateFin()->format('Y-m-d H:i:s'),
                'allDay' => $emprunt->isAllDay(),
                'display' => $emprunt->isAllDay() === true ? 'background' : '',
                'backgroundColor' => $emprunt->isAllDay() === true ? '#CC5448' : '',
            ];
        }
        return $calendarData;
    }


}