<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CheckNumCardsTest extends TestCase
{
    /**
     * Construct array with cardGraphic, demand one card, check if return correct.
     */
    public function testNumCard(): void
    {
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡") ];
        $checkNum = new CheckNumCards();
        $res = $checkNum->check(1, $currentDeck);

        $this->assertIsArray($res);
        $this->assertEquals(1, $res['cardsLeft']);
        $this->assertEquals(["🂡"], $res['cards']);
    }

    /**
     * Construct array with cardGraphic, demand too many cards, check if return correct.
     */
    public function testNumCardTooMany(): void
    {
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡") ];
        $checkNum = new CheckNumCards();
        $res = $checkNum->check(8, $currentDeck);

        $this->assertIsArray($res);
        $this->assertEquals(
            'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session',
            $res['warning']
        );
    }
}
