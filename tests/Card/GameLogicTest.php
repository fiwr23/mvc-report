<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GameLogic.
 */
class GameLogicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateGameLogic(): void
    {
        $gameLogic = new GameLogic();
        $this->assertInstanceOf("\App\Card\GameLogic", $gameLogic);
    }

    /**
     * Get data for new game.
     */
    public function testNewGame(): void
    {
        $gameLogic = new GameLogic();
        $res = $gameLogic->newGame();
        $this->assertIsArray($res);
        $this->assertIsArray($res['current_deck']);
        $this->assertEquals('player', $res['current_player']);
        $this->assertInstanceOf("\App\Card\CardHand", $res['bank_hand']);
        $this->assertInstanceOf("\App\Card\CardHand", $res['player_hand']);
        $this->assertEquals("-", $res['last_player_card']);
        $this->assertEquals("-", $res['last_bank_card']);
        $this->assertEquals([0], $res['player_hand_score']);
        $this->assertEquals([0], $res['bank_hand_score']);
        $this->assertEquals(0, $res['bank_wins']);
        $this->assertEquals(0, $res['player_wins']);
        $this->assertEquals("ongoing", $res['game_state']);
    }

    /**
     * Get data for next round.
     */
    public function testNextRound(): void
    {
        $gameLogic = new GameLogic();
        $res = $gameLogic->nextRound();

        $this->assertIsArray($res);
        $this->assertEquals('player', $res['current_player']);
        $this->assertInstanceOf("\App\Card\CardHand", $res['bank_hand']);
        $this->assertInstanceOf("\App\Card\CardHand", $res['player_hand']);
        $this->assertEquals("-", $res['last_player_card']);
        $this->assertEquals("-", $res['last_bank_card']);
        $this->assertEquals([0], $res['player_hand_score']);
        $this->assertEquals([0], $res['bank_hand_score']);
        $this->assertEquals("ongoing", $res['game_state']);
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

    /**
     * Check if game over.
     */
    public function testCheckIfGameOver(): void
    {
        $gameLogic = new GameLogic();
        // active is bank-> bank = 15 opponent-> player = 10
        $res = $gameLogic->checkIfGameOver([15], 'bank', [10]);

        $this->assertEquals("ongoing", $res);

        // active is bank-> bank = 21 opponent-> player = 10
        $res = $gameLogic->checkIfGameOver([21], 'bank', [10]);

        $this->assertEquals("win", $res);

        // active is bank-> bank = 11 opponent-> player = 15
        $res = $gameLogic->checkIfGameOver([11], 'bank', [15]);

        $this->assertEquals("ongoing", $res);

        // active is bank-> bank = 18 opponent-> player = 15
        $res = $gameLogic->checkIfGameOver([18], 'bank', [15]);

        $this->assertEquals("win", $res);

        // active is bank-> bank = 31 opponent-> player = 15
        $res = $gameLogic->checkIfGameOver([31], 'bank', [15]);

        $this->assertEquals("loss", $res);
    }

    /**
     * Lower complexity -> bank decides if staying plus result.
     */
    public function testLowerComplexity(): void
    {
        $gameLogic = new GameLogic();

        // bank is active score 18, opponent 31 and 15
        $res = $gameLogic->lowerComplexity('bank', 18, [31, 15]);

        $this->assertEquals("win", $res);

        // bank is active score 18, opponent 31 and 20
        $res = $gameLogic->lowerComplexity('bank', 18, [31, 20]);

        $this->assertEquals("loss", $res);

        // bank is active score 18, opponent 18
        $res = $gameLogic->lowerComplexity('bank', 18, [18]);
    }

    /**
     * Sums current hand returns sum(s) in array.
     */
    public function testsumCurrentHand(): void
    {
        $gameLogic = new GameLogic();
        $cardHand = new CardHand();
        $cardHand->add(new CardGraphic("Ace of Spades", "🂡"));

        $res = $gameLogic->sumCurrentHand($cardHand);

        $this->assertEquals([1, 14], $res);
    }

    /**
     * Converts array of int to string with ' or ' between
     */
    public function testscoreString(): void
    {
        $gameLogic = new GameLogic();
        $intArr = [1, 10];
        $res = $gameLogic->scoreString($intArr);

        $this->assertEquals("1 or 10", $res);

        $intArr = [13];
        $res = $gameLogic->scoreString($intArr);

        $this->assertEquals("13", $res);
    }
}
