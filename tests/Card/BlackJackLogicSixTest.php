<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogicSix.
 */
class BlackJackLogicSixTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackLogicSix(): void
    {
        $gameLogic = new BlackJackLogicSix();
        $this->assertInstanceOf("\App\Card\BlackJackLogicSix", $gameLogic);
    }

    /**
    * Test checks if all spots are done and it is banks turn.
    */
    public function testIsItBanksTurn(): void
    {
        // $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null

        $gameLogic = new BlackJackLogicSix();
        $playerSpotOne = [];
        $playerSpotOne['spot_state'] = 'started';
        $res = $gameLogic->isItBanksTurn($playerSpotOne);
        $this->assertEquals("no", $res);

        $playerSpotOne['spot_state'] = 'stay';
        $res = $gameLogic->isItBanksTurn($playerSpotOne);
        $this->assertEquals("yes", $res);

        $playerSpotOne['spot_state'] = 'won';
        $res = $gameLogic->isItBanksTurn($playerSpotOne);
        $this->assertEquals("yes", $res);

        $playerSpotOne['spot_state'] = 'loss';
        $res = $gameLogic->isItBanksTurn($playerSpotOne);
        $this->assertEquals("yes", $res);
    }

    /**
    * Test Bank's turn method with player stay.
    */
    public function testBanksTurn(): void
    {
        // int $money,
        // array $currentDeck,
        // CardHand $bankHand,
        // array $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null

        $gameLogic = new BlackJackLogicSix();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'stay';

        $res = $gameLogic->banksTurn(100, $currentDeck, $bankHand, $spotPlayer);

        $this->assertIsArray($res);
        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $this->assertEquals("Ace of Spades", $res['last_bank_card']);
        $this->assertInstanceOf("App\Card\CardHand", $res['bank_hand']);
    }

    /**
    * Test Bank's turn method with player won or loss.
    */
    public function testBanksTurn2(): void
    {
        // int $money,
        // array $currentDeck,
        // CardHand $bankHand,
        // array $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null

        $gameLogic = new BlackJackLogicSix();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ace of Spades", "🂡"));
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡"),];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ace of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'loss';
        $playerSpotTwo = $spotPlayer;
        $playerSpotTwo['spot_state'] = 'won';

        $res = $gameLogic->banksTurn(100, $currentDeck, $bankHand, $spotPlayer, $playerSpotTwo);

        $this->assertEquals("finished", $res['game_state']);
    }

    /**
    * Test Bank's turn method with player stay and valid bank score.
    */
    public function testBanksTurn3(): void
    {
        // int $money,
        // array $currentDeck,
        // CardHand $bankHand,
        // array $playerSpotOne,
        // array|null $playerSpotTwo = null,
        // array|null $playerSpotThree = null

        $gameLogic = new BlackJackLogicSix();
        $bankHand = new CardHand();
        $bankHand->add(new CardGraphic("Ten of Spades", "🂡"));
        $bankHand->add(new CardGraphic("Seven of Spades", "🂡"));
        $currentDeck = [new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡")];
        $spotPlayer = [];
        $spotPlayer['spot_player_hand'] = new CardHand();
        $spotPlayer['spot_player_hand']->add(new CardGraphic("Ten of Spades", "🂡"));
        $spotPlayer['spot_state'] = 'stay';

        $res = $gameLogic->banksTurn(100, $currentDeck, $bankHand, $spotPlayer);
        /**
         * @var array<array<CardGraphic>> $res
         */
        $this->assertInstanceOf('App\Card\CardGraphic', $res['current_deck'][0]);
    }

    /**
    * Test checkIfGameOver method.
    */
    public function testCheckIfGameOver(): void
    {
        $gameLogic = new BlackJackLogicSix();
        $res = $gameLogic->checkIfGameOver([12]);
        $this->assertEquals("ongoing", $res);
        $res = $gameLogic->checkIfGameOver([21]);
        $this->assertEquals("win", $res);
        $res = $gameLogic->checkIfGameOver([25]);
        $this->assertEquals("loss", $res);
    }

    /**
    * Test Compare two values and return winner.
    */
    public function testCompareHands(): void
    {
        // $money
        //  int $bankHighest
        // array $theSpot
        $gameLogic = new BlackJackLogicSix();
        $theSpot = [];
        $theSpot['spot_state'] = 'stay';
        $theSpot['spot_player_hand_score'] = [13, 20];
        $theSpot['spot_player_bet'] = 10;
        $res = $gameLogic->compareHands(100, 10, $theSpot);
        /**
         * @var array<array<string>> $res
         */
        $this->assertEquals(120, $res['money']);
        $this->assertEquals('won', $res['the_spot']['spot_state']);

    }
}
