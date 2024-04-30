<?php

namespace App\Card;

/**
 * Card class, holds card in text format.
 */
class Card
{
    /**
    * @var (string)
    */
    protected $textValue;
    /**
     * Card constructor.
    */
    public function __construct(string $textIn)
    {
        $this->textValue = $textIn;
    }

    /**
     * Returns card in text format.
     */
    public function getTextAsString(): string
    {
        return "{$this->textValue}";
    }
}
