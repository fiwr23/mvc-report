<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogicFive.
 */
class BlackJackLogicFiveTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackLogicFive(): void
    {
        $gameLogic = new BlackJackLogicFive();
        $this->assertInstanceOf("\App\Card\BlackJackLogicFive", $gameLogic);
    }

    /**
    * Test if the hit method works.
    */
    public function testHit(): void
    {
        // int $money,
        // array $currentDeckIn,
        // array $playerSpot,
        // string $activeSpot,
        // array $playerSpotOne,
        // $playerSpotTwo = null,
        // $playerSpotThree = null
        $gameLogic = new BlackJackLogicFive();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';

        $res = $gameLogic->hit(100, $currentDeck, $spotPlayer, 'player_spot_one', $spotPlayer);

        $this->assertIsArray($res);
        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res['player_spot_one']["spot_player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("started", $res['player_spot_one']["spot_state"]);
    }

    /**
    * Test if the hit method results in win and loss.
    */
    public function testHitWinLoss(): void
    {
        // int $money,
        // array $currentDeckIn,
        // array $playerSpot,
        // string $activeSpot,
        // array $playerSpotOne,
        // $playerSpotTwo = null,
        // $playerSpotThree = null
        $gameLogic = new BlackJackLogicFive();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';
        $spotPlayer["spot_player_bet"] = 10;

        $res = $gameLogic->hit(100, $currentDeck, $spotPlayer, 'player_spot_one', $spotPlayer);

        $this->assertIsArray($res);
        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res['player_spot_one']["spot_player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("won", $res['player_spot_one']["spot_state"]);

        // add another ten, should lose
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';

        $res = $gameLogic->hit(100, $currentDeck, $spotPlayer, 'player_spot_one', $spotPlayer);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("loss", $res['player_spot_one']["spot_state"]);

        // spotstate won or lostnext round
        $spotPlayerTwo = [];
        $spotPlayerTwo['spot_state'] = 'won';
        $spotPlayer['spot_state'] = 'won';

        $res = $gameLogic->hit(
            100,
            $currentDeck,
            $spotPlayer,
            'player_spot_one',
            $spotPlayer,
            $spotPlayerTwo
        );
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("finished", $res['game_state']);


    }

    /**
    * Checks if all spots have either won or lost.
    *
    */
    public function testAllSpotsWonOrLost(): void
    {
        // $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null
        $playerSpotOne = [];
        $playerSpotTwo = [];
        $playerSpotThree = [];
        $playerSpotOne['spot_state'] = "won";
        $playerSpotTwo['spot_state'] = "loss";
        $playerSpotThree['spot_state'] = "loss";

        $gameLogic = new BlackJackLogicFive();
        $res = $gameLogic->allSpotsWonOrLost($playerSpotOne);
        $this->assertEquals("yes", $res);

        $res = $gameLogic->allSpotsWonOrLost($playerSpotOne, $playerSpotTwo, $playerSpotThree);
        $this->assertEquals("yes", $res);

        $playerSpotTwo['spot_state'] = "stay";
        $res = $gameLogic->allSpotsWonOrLost($playerSpotOne, $playerSpotTwo, $playerSpotThree);
        /**
         * @var string $res
         */
        $this->assertEquals("no", $res);
    }
}
