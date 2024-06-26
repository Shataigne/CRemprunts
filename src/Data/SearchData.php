<?php

namespace App\Data;

use App\Entity\Centre;
use DateTime;

class SearchData
{
    /**
     * @var null|string
     */
    public ?string $q = '';

    /**
     * @var string[]
     */
    public array $marques = [];

    /**
     * @var string[]

    /**
     * @var Centre
     */
    public Centre $centres;


    public ?bool $dispoNow;

    public ?DateTime $dispoLe;

    public ?DateTime $dispoMin;

    public ?DateTime $dispoMax;

    public function setCentres(Centre $centre): void
    {
        $this->centres = $centre;
    }


}