<?php

namespace App\Card;

/**
 * CardGraphic class, holds card in graphic format.
 */
class CardGraphic extends Card
{
    /**
    * @var (string)
    */
    protected $graphicValue;

    /**
     * Card constructor.
     */
    public function __construct(string $textIn, string $graphicIn)
    {
        parent::__construct($textIn);
        $this->graphicValue = $graphicIn;
    }

    /**
     * Returns card as'graphic'.
     */
    public function getAsString(): string
    {
        return $this->graphicValue;
    }
}
