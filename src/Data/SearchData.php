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
     */
    public array $modeles = [];

    /**
     * @var Centre
     */
    public Centre $centres;


    public ?bool $dispoNow;


    public ?DateTime $dateMin;

    public ?DateTime $dateMax;

    public ?DateTime $TimeMin;

    public ?DateTime $TimeMax;

}