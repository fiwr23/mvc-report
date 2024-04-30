<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * More test cases for class GameLogic.
 */
class GameLogic2Test extends TestCase
{
    /**
     * Tests drawOneCard() with empty bank hand and player hand.
     * CardHand $cardHandIn,
     *    array $currentDeckIn,
     *   string $whoIn,
     *   CardHand $opponentHandIn,
     *   int $playerWinsIn,
     *   int $bankWinsIn
     */
    public function testDrawOneCard(): void
    {
        $gameLogic = new GameLogic();
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡") ];
        $activeCardHand = new CardHand();
        $opponentCardHand = new CardHand();
        $res = $gameLogic->drawOneCard(
            $activeCardHand,
            $currentDeck,
            "bank",
            $opponentCardHand,
            0,
            0
        );

        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res["bank_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("Ace of Spades", $res["last_bank_card"]);
        $this->assertEquals([1, 14], $res["bank_hand_score"]);
        $this->assertEquals("ongoing", $res["game_state"]);
        $this->assertEquals(0, $res["player_wins"]);
        $this->assertEquals(0, $res["bank_wins"]);



        /*
            "current_deck" => $currentDeck,
            $currentPlayer . "_hand" => $cardHand,
            "last_" . $currentPlayer . "_card" => $lastPlayerCard,
            $currentPlayer . "_hand_score" => $currentHandScore,
            "game_state" => $gameState,
            "player_wins" => $playerWins,
            "bank_wins" => $bankWins
        */
    }

    /**
     * Tests drawOneCard() with card in bank hand to lose.
     * CardHand $cardHandIn,
     *    array $currentDeckIn,
     *   string $whoIn,
     *   CardHand $opponentHandIn,
     *   int $playerWinsIn,
     *   int $bankWinsIn
     */
    public function testDrawOneCard2(): void
    {
        $gameLogic = new GameLogic();
        $currentDeck = [
            new CardGraphic("King of Clubs", "🃞"), new CardGraphic("King of Clubs", "🃞") ];
        $activeCardHand = new CardHand();
        $activeCardHand->add(new CardGraphic("King of Clubs", "🃞"));
        $opponentCardHand = new CardHand();
        $res = $gameLogic->drawOneCard(
            $activeCardHand,
            $currentDeck,
            "bank",
            $opponentCardHand,
            0,
            0
        );

        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res["bank_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[1]->getAsString();
        $this->assertEquals("🃞", $cardGraphicValue);
        $this->assertEquals("King of Clubs", $res["last_bank_card"]);
        $this->assertEquals([26], $res["bank_hand_score"]);
        $this->assertEquals("loss", $res["game_state"]);
        $this->assertEquals(1, $res["player_wins"]);
        $this->assertEquals(0, $res["bank_wins"]);
    }

    /**
     * Tests drawOneCard() with card in player hand to lose.
     * CardHand $cardHandIn,
     *    array $currentDeckIn,
     *   string $whoIn,
     *   CardHand $opponentHandIn,
     *   int $playerWinsIn,
     *   int $bankWinsIn
     */
    public function testDrawOneCard3(): void
    {
        $gameLogic = new GameLogic();
        $currentDeck = [
            new CardGraphic("King of Clubs", "🃞"), new CardGraphic("King of Clubs", "🃞") ];
        $activeCardHand = new CardHand();
        $activeCardHand->add(new CardGraphic("King of Clubs", "🃞"));
        $opponentCardHand = new CardHand();
        $res = $gameLogic->drawOneCard(
            $activeCardHand,
            $currentDeck,
            "player",
            $opponentCardHand,
            0,
            0
        );

        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res["player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[1]->getAsString();
        $this->assertEquals("🃞", $cardGraphicValue);
        $this->assertEquals("King of Clubs", $res["last_player_card"]);
        $this->assertEquals([26], $res["player_hand_score"]);
        $this->assertEquals("loss", $res["game_state"]);
        $this->assertEquals(0, $res["player_wins"]);
        $this->assertEquals(1, $res["bank_wins"]);
    }

    /**
     * Tests drawOneCard() with card in bank hand to win.
     * CardHand $cardHandIn,
     *    array $currentDeckIn,
     *   string $whoIn,
     *   CardHand $opponentHandIn,
     *   int $playerWinsIn,
     *   int $bankWinsIn
     */
    public function testDrawOneCard4(): void
    {
        $gameLogic = new GameLogic();
        $currentDeck = [
            new CardGraphic("Four of Hearts", "🂴"), new CardGraphic("Four of Hearts", "🂴") ];
        $activeCardHand = new CardHand();
        $activeCardHand->add(new CardGraphic("King of Clubs", "🃞"));
        $opponentCardHand = new CardHand();
        $res = $gameLogic->drawOneCard(
            $activeCardHand,
            $currentDeck,
            "bank",
            $opponentCardHand,
            0,
            0
        );

        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res["bank_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[1]->getAsString();
        $this->assertEquals("🂴", $cardGraphicValue);
        $this->assertEquals("Four of Hearts", $res["last_bank_card"]);
        $this->assertEquals([17], $res["bank_hand_score"]);
        $this->assertEquals("win", $res["game_state"]);
        $this->assertEquals(0, $res["player_wins"]);
        $this->assertEquals(1, $res["bank_wins"]);
    }

    /**
     * Tests drawOneCard() with card in player hand to win.
     * CardHand $cardHandIn,
     *    array $currentDeckIn,
     *   string $whoIn,
     *   CardHand $opponentHandIn,
     *   int $playerWinsIn,
     *   int $bankWinsIn
     */
    public function testDrawOneCard5(): void
    {
        $gameLogic = new GameLogic();
        $currentDeck = [
            new CardGraphic("Four of Hearts", "🂴"), new CardGraphic("Four of Hearts", "🂴") ];
        $activeCardHand = new CardHand();
        $activeCardHand->add(new CardGraphic("King of Clubs", "🃞"));
        $activeCardHand->add(new CardGraphic("Four of Hearts", "🂴"));
        $opponentCardHand = new CardHand();
        $res = $gameLogic->drawOneCard(
            $activeCardHand,
            $currentDeck,
            "player",
            $opponentCardHand,
            0,
            0
        );

        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res["player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[2]->getAsString();
        $this->assertEquals("🂴", $cardGraphicValue);
        $this->assertEquals("Four of Hearts", $res["last_player_card"]);
        $this->assertEquals([21], $res["player_hand_score"]);
        $this->assertEquals("win", $res["game_state"]);
        $this->assertEquals(1, $res["player_wins"]);
        $this->assertEquals(0, $res["bank_wins"]);
    }
}
