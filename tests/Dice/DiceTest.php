<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Dice.
 */
class DiceTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDice(): void
    {
        $die = new Dice();
        $this->assertInstanceOf("\App\Dice\Dice", $die);

        $res = $die->getAsString();
        $this->assertNotEmpty($res);
    }

    /**
     * Construct object and verify that the object gets expected
     * value, with no arguments.
     */
    public function testGetValue(): void
    {
        $die = new Dice();

        $res = $die->getValue();
        $this->assertEmpty($res);
        $res = $die->roll();
        $this->assertGreaterThan(0, $res);
        $this->assertLessThan(7, $res);
    }

    /**
     * Construct object and verify that the object returns expected
     * value when roll().
     */
    public function testRoll(): void
    {
        $die = new Dice();

        $res = $die->roll();
        $this->assertIsInt($res);
    }
}
