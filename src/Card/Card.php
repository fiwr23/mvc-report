<?php

namespace App\Card;

class Card
{
    protected $textValue;

    public function __construct(string $textIn)
    {
        $this->textValue = $textIn;
    }

    public function roll(): int
    {
        $this->value = random_int(1, 6);
        return $this->textValue;
    }

    public function getValue(): string
    {
        return $this->textValue;
    }

    public function getAsString(): string
    {
        return "{$this->textValue}";
    }
}
