<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogicTwo.
 */
class BlackJackLogicTwoTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackLogicTwo(): void
    {
        $gameLogic = new BlackJackLogicTwo();
        $this->assertInstanceOf("\App\Card\BlackJackLogicTwo", $gameLogic);
    }

    /**
     * Test creating new game.
     */
    public function testNewGame(): void
    {  // $playerName,
        // $numCardHands,
        // $allBets,
        // array|null $currentDeck = null
        $gameLogic = new BlackJackLogicTwo();
        $res = $gameLogic->newGame('Namn', 3, [1, 2, 3]);
        $this->assertIsArray($res);
        $this->assertEquals($res['player_name'], 'Namn');
        $this->assertEquals($res['player_spot_one']['spot_player_bet'], 1);
        $this->assertEquals($res['player_spot_two']['spot_player_bet'], 2);
        $this->assertEquals($res['player_spot_three']['spot_player_bet'], 3);
    }

    /**
    * Test return scores as string.
    */
    public function testScoreString(): void
    {
        $gameLogic = new BlackJackLogicTwo();
        $res = $gameLogic->scoreString([1, 2]);
        //array $arrayIn
        $this->assertEquals($res, '1 or 2');
    }

    /**
    * Test reset everything.
    */
    public function testNextRound(): void
    {
        $gameLogic = new BlackJackLogicTwo();
        $res = $gameLogic->nextRound();

        $this->assertIsArray($res);
        $this->assertEquals($res['current_player'], 'player');
        $this->assertInstanceOf("\App\Card\CardHand", $res['bank_hand']);
        $this->assertEquals($res["last_bank_card"], "-");
        $this->assertEquals($res['bank_hand_score'], [0]);
        $this->assertEquals($res['game_state'], 'ongoing');
    }

    /**
     * Returns player's cardhand score(s) as string.
    */
    public function testScoreString2(): void
    {
        $gameLogic = new BlackJackLogicTwo();
        $res = $gameLogic->scoreString([5]);

        $this->assertEquals($res, '5');

        $res = $gameLogic->scoreString([5, 6]);

        $this->assertEquals($res, '5 or 6');

    }
}
