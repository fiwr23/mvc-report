<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use string as argument.
     */
    public function testCreateCardGraphic(): void
    {
        $cardGraphic = new CardGraphic("Test1", "Test2");
        $this->assertInstanceOf("\App\Card\CardGraphic", $cardGraphic);

        $res = $cardGraphic->getAsString();
        $this->assertNotEmpty($res);
    }

}
