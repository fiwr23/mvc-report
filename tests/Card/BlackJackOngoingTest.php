<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackOngoing.
 */
class BlackJackLogicOngoingTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackOngoing(): void
    {
        $gameLogic = new BlackJackOngoing();
        $this->assertInstanceOf("\App\Card\BlackJackOngoing", $gameLogic);
    }

    /**
    *  Test ongoing data creator.
    */
    public function testOngoingDataCreator(): void
    {
        // string $gameState,
        // int $money,
        // string $lastBankCard,
        // CardHand $bankHand,
        // array $bankHandScore,
        // string $currentPlayer,
        // string $playerName,
        // array $someData

        $gameLogic = new BlackJackOngoing();
        $gameState = 'ongoing';
        $money = 100;
        $lastBankCard = "Ace of Spades";
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $bankHandScore = [1, 11];
        $currentPlayer = 'player';
        $playerName = 'Namnet';
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_player_hand_score'] = [1, 11];
        $spotPlayer['spot_state'] = 'started';
        $someData = [ 'player_spot_one' => $spotPlayer,
            'player_spot_two' => null,
            'player_spot_three' => null,
            'bet1' => 10,
            'bet2' => 0,
            'bet3' => 0,
            'num_card_hands' => 1];

        $res = $gameLogic->ongoingDataCreator(
            $gameState,
            $money,
            $lastBankCard,
            $bankHand,
            $bankHandScore,
            $currentPlayer,
            $playerName,
            $someData
        );

        $this->assertIsArray($res);
        $cardGraphicArray =  $res['player_spot_one']["spot_player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("started", $res['player_spot_one']["spot_state"]);
    }

    /**
    *  Test ongoing data creator, 3 spots.
    */
    public function testOngoingDataCreator3Spots(): void
    {
        // string $gameState,
        // int $money,
        // string $lastBankCard,
        // CardHand $bankHand,
        // array $bankHandScore,
        // string $currentPlayer,
        // string $playerName,
        // array $someData

        $gameLogic = new BlackJackOngoing();
        $gameState = 'ongoing';
        $money = 100;
        $lastBankCard = "Ace of Spades";
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $bankHandScore = [1, 11];
        $currentPlayer = 'player';
        $playerName = 'Namnet';
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_player_hand_score'] = [1, 11];
        $spotPlayer['spot_state'] = 'started';
        $someData = [ 'player_spot_one' => $spotPlayer,
            'player_spot_two' => $spotPlayer,
            'player_spot_three' => $spotPlayer,
            'bet1' => 10,
            'bet2' => 0,
            'bet3' => 0,
            'num_card_hands' => 3];

        $res = $gameLogic->ongoingDataCreator(
            $gameState,
            $money,
            $lastBankCard,
            $bankHand,
            $bankHandScore,
            $currentPlayer,
            $playerName,
            $someData
        );

        $this->assertIsArray($res);
        $cardGraphicArray =  $res['player_spot_two']["spot_player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("started", $res['player_spot_three']["spot_state"]);
    }
}
