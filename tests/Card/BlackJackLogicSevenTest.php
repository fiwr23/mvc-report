<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogicSeven.
 */
class BlackJackLogicSevenTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackLogicSeven(): void
    {
        $gameLogic = new BlackJackLogicSeven();
        $this->assertInstanceOf("\App\Card\BlackJackLogicSeven", $gameLogic);
    }

    /**
    * Test checks if bank has 17-20 or 21 or >21 method, here <17.
    * Should return empty array or gamestate = finished if spot is loss/win
    *
    */
    public function testCheckBankAndSpotScores(): void
    {    // int $money,
        // CardHand $bankHand,
        // array $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null
        $gameLogic = new BlackJackLogicSeven();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'stay';
        $spotPlayer["spot_player_hand_score"] = [1, 11];
        $res = $gameLogic->checkBankAndSpotScores(100, $bankHand, $spotPlayer);

        $this->assertIsArray($res);
        $this->assertEquals([], $res);

        $spotPlayer['spot_state'] = 'loss';
        $res = $gameLogic->checkBankAndSpotScores(100, $bankHand, $spotPlayer);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals('finished', $res['game_state']);
    }

    /**
    * Test checks if bank has 17-20 or 21 or >21 method, here 21.
    *
    */
    public function testCheckBankAndSpotScores2(): void
    {    // int $money,
        // CardHand $bankHand,
        // array $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null
        $gameLogic = new BlackJackLogicSeven();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $bankHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'stay';
        $spotPlayer["spot_player_hand_score"] = [1, 11];
        $res = $gameLogic->checkBankAndSpotScores(100, $bankHand, $spotPlayer);

        $this->assertIsArray($res);
        $this->assertEquals('loss', $res['player_spot_one']['spot_state']);
        $spotPlayerTwo = $spotPlayer;
        $spotPlayerThree = $spotPlayer;
        $res = $gameLogic->checkBankAndSpotScores(
            100,
            $bankHand,
            $spotPlayer,
            $spotPlayerTwo,
            $spotPlayerThree
        );

        $this->assertIsArray($res);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals('loss', $res['player_spot_two']['spot_state']);
        $this->assertEquals('loss', $res['player_spot_three']['spot_state']);

    }

    /**
    * Test checks if bank has 17-20 or 21 or >21 method, here > 21.
    *
    */
    public function testCheckBankAndSpotScores3(): void
    {    // int $money,
        // CardHand $bankHand,
        // array $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null
        $gameLogic = new BlackJackLogicSeven();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $bankHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $bankHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $bankHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'stay';
        $spotPlayer['spot_player_bet'] = 50;
        $spotPlayer["spot_player_hand_score"] = [1, 11];
        $res = $gameLogic->checkBankAndSpotScores(100, $bankHand, $spotPlayer);

        $this->assertIsArray($res);
        $this->assertEquals(200, $res['money']);
        $spotPlayerTwo = $spotPlayer;
        $spotPlayerThree = $spotPlayer;
        $res = $gameLogic->checkBankAndSpotScores(
            100,
            $bankHand,
            $spotPlayer,
            $spotPlayerTwo,
            $spotPlayerThree
        );
        /**
         * @var array<array<int>> $res
         */
        $this->assertEquals(400, $res['money']);
    }

    /**
    * Test checks if bank has 17-20 or 21 or >21 method, here 18.
    *
    */
    public function testCheckBankAndSpotScores4(): void
    {    // int $money,
        // CardHand $bankHand,
        // array $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null
        $gameLogic = new BlackJackLogicSeven();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $bankHand->add(new CardGraphic("Eight of Spades", "🂡"));
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'stay';
        $spotPlayer["spot_player_hand_score"] = [1, 11];
        $res = $gameLogic->checkBankAndSpotScores(100, $bankHand, $spotPlayer);

        $this->assertIsArray($res);
        $this->assertEquals('loss', $res['player_spot_one']['spot_state']);
        $spotPlayerTwo = $spotPlayer;
        $spotPlayerThree = $spotPlayer;
        $res = $gameLogic->checkBankAndSpotScores(
            100,
            $bankHand,
            $spotPlayer,
            $spotPlayerTwo,
            $spotPlayerThree
        );

        $this->assertIsArray($res);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals('loss', $res['player_spot_two']['spot_state']);
        $this->assertEquals('loss', $res['player_spot_three']['spot_state']);
        $this->assertEquals('finished', $res['game_state']);

    }

}
