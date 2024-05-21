<?php

namespace App\Card;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJackLogicThree.
 */
class BlackJackLogicThreeTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties.
     */
    public function testCreateBlackJackLogicThree(): void
    {
        $gameLogic = new BlackJackLogicThree();
        $this->assertInstanceOf("\App\Card\BlackJackLogicThree", $gameLogic);
    }

    /**
    * Test draw card method.
    */
    public function testFirstCardDraw(): void
    {
        // CardHand $cardHandIn,
        // array $currentDeckIn,
        // string $whoIn,
        $gameLogic = new BlackJackLogicThree();
        $cardHand = new CardHand();
        $currentDeck = [
            new CardGraphic("Ace of Spades", "🂡"), new CardGraphic("Ace of Spades", "🂡") ];
        $res = $gameLogic->firstCardDraw($cardHand, $currentDeck, 'player');

        $this->assertIsArray($res["current_deck"]);
        $this->assertInstanceOf("App\Card\CardGraphic", $res["current_deck"][0]);
        $cardGraphicArray =  $res["player_hand"]->getAsArray();
        $cardGraphicValue = $cardGraphicArray[0]->getAsString();
        $this->assertEquals("🂡", $cardGraphicValue);
        $this->assertEquals("Ace of Spades", $res['last_player_card']);
        $this->assertEquals([1, 11], $res['player_hand_score']);
    }
}
