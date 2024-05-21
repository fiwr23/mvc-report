<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogicFour.
 */
class BlackJackLogicFourTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackLogicFour(): void
    {
        $gameLogic = new BlackJackLogicFour();
        $this->assertInstanceOf("\App\Card\BlackJackLogicFour", $gameLogic);
    }

    /**
    * Test second round of cards method.
    */
    public function testSecondRoundOfCards(): void
    {
        // CardHand $bankHand,
        // int $money,
        // $currentDeckIn,
        // $playerSpotOne,
        //  $playerSpotTwo
        //  $playerSpotThree
        $gameLogic = new BlackJackLogicFour();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';

        $res = $gameLogic->secondRoundOfCards($bankHand, 100, $currentDeck, $spotPlayer);

        $this->assertIsArray($res);
        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res['player_spot_one']["spot_player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("started", $res['player_spot_one']["spot_state"]);

        //playerspottwo and three active
        $res = $gameLogic->secondRoundOfCards(
            $bankHand,
            100,
            $currentDeck,
            $spotPlayer,
            $spotPlayer,
            $spotPlayer
        );
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("started", $res['player_spot_two']["spot_state"]);
        $this->assertEquals("started", $res['player_spot_three']["spot_state"]);

    }

    /**
    * Test second round of cards method Player wins.
    */
    public function testSecondRoundOfCardsWin(): void
    {
        // CardHand $bankHand,
        // int $money,
        // $currentDeckIn,
        // $playerSpotOne,
        //  $playerSpotTwo
        //  $playerSpotThree
        $gameLogic = new BlackJackLogicFour();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡")];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';
        $spotPlayer['spot_player_bet'] = 1;

        $res = $gameLogic->secondRoundOfCards($bankHand, 100, $currentDeck, $spotPlayer);
        /**
         * @var array<array<CardHand>> $res
         */
        $cardGraphicArray =  $res['player_spot_one']["spot_player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getTextAsString();
        $this->assertEquals("Ten of Spades", $cardGraphicValue);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("won", $res['player_spot_one']["spot_state"]);
        $this->assertEquals(102, $res['money']);


        //playerspottwo and three active

        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';
        $spotPlayer['spot_player_bet'] = 1;
        $spotPlayerTwo = $spotPlayer;
        $spotPlayerThree = $spotPlayer;
        $res = $gameLogic->secondRoundOfCards(
            $bankHand,
            100,
            $currentDeck,
            $spotPlayerTwo,
            $spotPlayer,
            $spotPlayerThree
        );

        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("started", $res['player_spot_two']["spot_state"]);
        $this->assertEquals("started", $res['player_spot_three']["spot_state"]);
        // $this->assertEquals(106, $res['money']);
    }

    /**
    * Test second round of cards method Player wins.
    */
    public function testSecondRoundOfCardsLoss(): void
    {
        // CardHand $bankHand,
        // int $money,
        // $currentDeckIn,
        // $playerSpotOne,
        //  $playerSpotTwo
        //  $playerSpotThree
        $gameLogic = new BlackJackLogicFour();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';
        $spotPlayer['spot_player_bet'] = 1;

        $res = $gameLogic->secondRoundOfCards($bankHand, 100, $currentDeck, $spotPlayer);

        /**
         * @var array<array<CardHand>> $res
         */
        $this->assertEquals(4, count($res['player_spot_one']["spot_player_hand"]->getAsArray()));
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("loss", $res['player_spot_one']["spot_state"]);

        //playerspottwo and three active
        $res = $gameLogic->secondRoundOfCards(
            $bankHand,
            100,
            $currentDeck,
            $spotPlayer,
            $spotPlayer,
            $spotPlayer
        );

        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("loss", $res['player_spot_two']["spot_state"]);
        $this->assertEquals("loss", $res['player_spot_three']["spot_state"]);
    }

    /**
    * Test drawn card spot if not null,  Win and loss.
    */
    public function testNewCardSpot(): void
    {
        // array|null $playerSpot,
        // array $currentDeck,
        // int $money
        $gameLogic = new BlackJackLogicFour();
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));

        $spotPlayer['spot_state'] = 'started';
        $spotPlayer['spot_player_bet'] = 1;

        $res = $gameLogic->newCardSpot($spotPlayer, $currentDeck, 100);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("won", $res['player_spot']["spot_state"]);
        $this->assertEquals(102, $res['money']);

        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'started';
        $spotPlayer['spot_player_bet'] = 1;

        $res = $gameLogic->newCardSpot($spotPlayer, $currentDeck, 100);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals("loss", $res['player_spot']["spot_state"]);
        $this->assertEquals(100, $res['money']);
    }

}
