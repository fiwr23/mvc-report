<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GameLogicTwo.
 */
class GameLogicTwoTest extends TestCase
{
    /**
     * Get data for new game.
     */
    public function testNewGame(): void
    {
        $gameLogic = new GameLogicTwo();
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
        $gameLogic = new GameLogicTwo();
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
     * Converts array of int to string with ' or ' between
     */
    public function testscoreString(): void
    {
        $gameLogic = new GameLogicTwo();
        $intArr = [1, 10];
        $res = $gameLogic->scoreString($intArr);

        $this->assertEquals("1 or 10", $res);

        $intArr = [13];
        $res = $gameLogic->scoreString($intArr);

        $this->assertEquals("13", $res);
    }
}
