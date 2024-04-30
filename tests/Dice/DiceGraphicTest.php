<?php

namespace App\Dice;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class DiceGraphic.
 */
class DiceGraphicTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */
    public function testCreateDiceGraphic(): void
    {
        $die = new DiceGraphic();
        $this->assertInstanceOf("\App\Dice\DiceGraphic", $die);
        $die->roll();

        $res = $die->getAsString();
        $this->assertIsString($res);
    }
}
