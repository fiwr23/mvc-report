<?php

namespace App\Card;

class CardGraphic extends Card
{
    /**
    * @var (string)
    */
    protected $graphicValue;

    public function __construct(string $textIn, string $graphicIn)
    {
        parent::__construct($textIn);
        $this->graphicValue = $graphicIn;
    }

    public function getAsString(): string
    {
        return $this->graphicValue;
    }
}
