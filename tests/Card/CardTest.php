<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use string as argument.
     */
    public function testCreateCard(): void
    {
        $card = new Card("Test");
        $this->assertInstanceOf("\App\Card\Card", $card);

        $res = $card->getTextAsString();
        $this->assertNotEmpty($res);
    }

}
