<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogic.
 */
class BlackJackLogicBTest extends TestCase
{
    /**
     * Bank draws one card and check if win, should win, opponent too high.
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
        $opponentHand->add(new CardGraphic("Ten of Spades", "🂡"));
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
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("Ten of Spades", $res["last_bank_card"]);
        $this->assertEquals(0, $res["player_wins"]);
        $this->assertEquals(1, $res["bank_wins"]);
    }

    /**
     * Bank draws one card and check if win, should lose.
     *
     */
    public function testDrawOneCardBankLose(): void
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
        $this->assertEquals(1, $res["player_wins"]);
        $this->assertEquals(0, $res["bank_wins"]);

        // bank 17 opponent 20
        $opponentHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $cardHand = new CardHand();
        $cardHand->add(new CardGraphic("Seven of Spades", "🂡"));
        $res = $gameLogic->drawOneCard(
            $cardHand,
            $currentDeck,
            $who,
            $opponentHand,
            $playerWins,
            $bankWins
        );
        /**
         * @var array<array<int>> $res
         */
        $this->assertEquals(1, $res["player_wins"]);
        $this->assertEquals(0, $res["bank_wins"]);
    }
}
