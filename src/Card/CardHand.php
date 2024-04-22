<?php

namespace App\Card;

use App\Card\CardGraphic;

class CardHand
{
    /**
    * @var (array<CardGraphic>)
    */
    protected $cardGraphicArray = [];

    public function __construct()
    {
        $this->cardGraphicArray = [];
    }

    public function add(CardGraphic $cardGraphicIn): void
    {
        array_push($this->cardGraphicArray, $cardGraphicIn);
    }

    /**
    * @return array<CardGraphic>
    */
    public function getAsArray(): array
    {
        return $this->cardGraphicArray;
    }
}
