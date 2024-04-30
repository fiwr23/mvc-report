<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use string as argument.
     */
    public function testCreateCardHand(): void
    {
        $cardHand = new CardHand();
        $this->assertInstanceOf("\App\Card\CardHand", $cardHand);
    }

    /**
     * Construct object add card and verify that the object has the expected
     * properties, use CardGraphic as argument.
     */
    public function testGetAsArrayCardHand(): void
    {
        $cardGraphic = new CardGraphic("Test1", "Test2");
        $cardHand = new CardHand();
        $cardHand->add($cardGraphic);
        $res = $cardHand->getAsArray();

        $this->assertInstanceOf("\App\Card\CardGraphic", $res[0]);
    }

}
