<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * CardHand class, holds cardGraphic in array.
 */
class CardHand
{
    /**
    * @var (array<CardGraphic>)
    */
    protected $cardGraphicArray = [];

    /**
    * CardHand constructor.
    */
    public function __construct()
    {
        $this->cardGraphicArray = [];
    }

    /**
    * Adds CardGraphic to cardGraphicArray.
    */
    public function add(CardGraphic $cardGraphicIn): void
    {
        array_push($this->cardGraphicArray, $cardGraphicIn);
    }

    /**
     * Returns array with all cardgraphic instances.
     *
     * @return array<CardGraphic>
    */
    public function getAsArray(): array
    {
        return $this->cardGraphicArray;
    }
}
