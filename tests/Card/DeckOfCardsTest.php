<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateDeckOfCards(): void
    {
        $deckOfCards = new DeckOfCards();
        $this->assertInstanceOf("\App\Card\DeckOfCards", $deckOfCards);
    }

    /**
     * Construct deckofcards and get all cards in order.
     */
    public function testGetAllCardsInOrder(): void
    {
        $deckOfCards = new DeckOfCards();
        $res = $deckOfCards->getAllCardsInOrder();
        $this->assertCount(52, $res);

        // "🂡"
        $firstValueAsString = $res[0]->getAsString();

        $this->assertEquals("🂡", $firstValueAsString);

        // "🃞"
        /** @var CardGraphic $lastValue*/
        $lastValue = end($res);
        $lastValueAsString = $lastValue->getAsString();

        $this->assertEquals("🃞", $lastValueAsString);
    }

    /**
     * Construct deckofcards and get all cards shuffled.
     */
    public function testGetAllCardsShuffled(): void
    {
        $deckOfCards = new DeckOfCards();
        $res = $deckOfCards->getAllCardsShuffled();
        $this->assertCount(52, $res);

        // "🂡" "🃞"
        $firstValueAsString = $res[0]->getAsString();
        /** @var CardGraphic $lastValue*/
        $lastValue = end($res);
        $lastValueAsString = $lastValue->getAsString();

        $this->assertFalse($firstValueAsString === "🂡" && $lastValueAsString === "🃞");
    }
}
