<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceHand.
 */
class DiceHandTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateHand(): void
    {
        $diceHand = new DiceHand();
        $this->assertInstanceOf("\App\Dice\DiceHand", $diceHand);

        $res = $diceHand->getNumberDices();
        $this->assertIsInt($res);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties with one dice.
     */
    public function testWithOneDice(): void
    {
        $diceHand = new DiceHand();
        $die = new Dice();
        $die->roll();
        $diceHand->add($die);
        $res = $diceHand->getNumberDices();
        $this->assertIsInt($res);
    }

    /**
     * Construct object and verify that the object has the expected
     * properties after roll.
     */
    public function testRollDiceHand(): void
    {
        $diceHand = new DiceHand();
        $die = new Dice();
        $die->roll();
        $diceHand->add($die);
        $diceHand->roll();
        $res = $diceHand->getNumberDices();
        $this->assertIsInt($res);
    }

    /**
     * Construct object and verify that the object returns an array.
     */
    public function testGetIntValues(): void
    {
        $diceHand = new DiceHand();
        $die = new Dice();
        $die->roll();
        $diceHand->add($die);
        $res = $diceHand->getValues();
        $this->assertIsArray($res);
    }

    /**
     * Construct object and verify that the object returns an array.
     */
    public function testGetStringValues(): void
    {
        $diceHand = new DiceHand();
        $die = new Dice();
        $die->roll();
        $diceHand->add($die);
        $res = $diceHand->getString();
        $this->assertIsArray($res);
    }
}
