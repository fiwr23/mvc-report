<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogic.
 */
class BlackJackLogicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackLogic(): void
    {
        $gameLogic = new BlackJackLogic();
        $this->assertInstanceOf("\App\Card\BlackJackLogic", $gameLogic);
    }

    /**
     * Draw one card and check if win but no win.
     *
     */
    public function testDrawOneCard(): void
    {
        $gameLogic = new BlackJackLogic();
        $cardHand = new CardHand();

        // $cardHandIn
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡") ];
        // array $currentDeckIn,
        $who = 'player';
        // string $whoIn,

        // CardHand $opponentHandIn,
        $opponentHand = new CardHand();
        $playerWins = 0;
        $bankWins = 0;
        // int $playerWinsIn,
        // int $bankWinsIn
        $cardHand->add($currentDeck[0]);
        $res = $gameLogic->drawOneCard(
            $cardHand,
            $currentDeck,
            $who,
            $opponentHand,
            $playerWins,
            $bankWins
        );
        $this->assertIsArray($res);
        $cardGraphicArray =  $res["player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("Ace of Spades", $res["last_player_card"]);
        $this->assertEquals("ongoing", $res["game_state"]);
        $this->assertEquals(0, $res["player_wins"]);
        $this->assertEquals(0, $res["bank_wins"]);
    }

    /**
     * Draw one card and check if win, should lose.
     *
     */
    public function testDrawOneCardLose(): void
    {
        $gameLogic = new BlackJackLogic();
        $cardHand = new CardHand();

        // $cardHandIn
        // array $currentDeckIn,
        $who = 'player';
        // string $whoIn,

        $currentDeck = [
            new CardGraphic("Ten of Spades", "🂡"), new CardGraphic("Ten of Spades", "🂡"),
            new CardGraphic("Ten of Spades", "🂡"), new CardGraphic("Ten of Spades", "🂡") ];
        // CardHand $opponentHandIn,

        $opponentHand = new CardHand();
        $playerWins = 0;
        $bankWins = 0;
        // add two tens to cardhand before drawing next
        $cardHand->add($currentDeck[0]);
        $cardHand->add($currentDeck[0]);
        $opponentHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $opponentHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $res = $gameLogic->drawOneCard(
            $cardHand,
            $currentDeck,
            $who,
            $opponentHand,
            $playerWins,
            $bankWins
        );
        $this->assertIsArray($res);
        $cardGraphicArray =  $res["player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("Ten of Spades", $res["last_player_card"]);
        $this->assertEquals(0, $res["player_wins"]);
        $this->assertEquals(1, $res["bank_wins"]);
        $this->assertEquals([30], $res["player_hand_score"]);
    }

    /**
     * Draw one card and check if win, should win.
     *
     */
    public function testDrawOneCardWin(): void
    {
        $gameLogic = new BlackJackLogic();
        $cardHand = new CardHand();

        // $cardHandIn
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡") ];
        // array $currentDeckIn,
        $who = 'player';
        // string $whoIn,

        // CardHand $opponentHandIn,
        $opponentHand = new CardHand();
        $opponentHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $playerWins = 0;
        $bankWins = 0;

        $cardHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $cardHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $res = $gameLogic->drawOneCard(
            $cardHand,
            $currentDeck,
            $who,
            $opponentHand,
            $playerWins,
            $bankWins
        );
        $this->assertIsArray($res);
        $cardGraphicArray =  $res["player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("Ace of Spades", $res["last_player_card"]);
        $this->assertEquals(1, $res["player_wins"]);
        $this->assertEquals(0, $res["bank_wins"]);
    }

    /**
     * Bank draws one card and check if win, should win.
     *
     */
    public function testDrawOneCardBankWin(): void
    {
        $gameLogic = new BlackJackLogic();
        $cardHand = new CardHand();

        // $cardHandIn
        $currentDeck = [
            new CardGraphic("Ten of Spades", "🂡"), new CardGraphic("Ten of Spades", "🂡"),
            new CardGraphic("Ten of Spades", "🂡"), new CardGraphic("Ten of Spades", "🂡") ];
        // array $currentDeckIn,
        $who = 'bank';

        // CardHand $opponentHandIn,
        $opponentHand = new CardHand();
        $opponentHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $playerWins = 0;
        $bankWins = 0;

        $cardHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $res = $gameLogic->drawOneCard(
            $cardHand,
            $currentDeck,
            $who,
            $opponentHand,
            $playerWins,
            $bankWins
        );
        $this->assertIsArray($res);
        $cardGraphicArray =  $res["bank_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("Ten of Spades", $res["last_bank_card"]);
        $this->assertEquals(0, $res["player_wins"]);
        $this->assertEquals(1, $res["bank_wins"]);
    }
}
