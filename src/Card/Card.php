<?php

namespace App\Card;

class Card
{
    /**
    * @var (string)
    */
    protected $textValue;

    public function __construct(string $textIn)
    {
        $this->textValue = $textIn;
    }


    public function getTextAsString(): string
    {
        return "{$this->textValue}";
    }
}
