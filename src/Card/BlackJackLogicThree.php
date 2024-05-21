<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* BlackJackLogicThree class, contains game functions.
*/
class BlackJackLogicThree
{
    /* public function testFunc(SessionInterface $session): string
    {
        $playerName = $session->get('player_name');
        if (!$playerName) {
            $playerName = "";
        }

        return $playerName;
    } */

    /**
    * Draw card method.
    * @param CardHand $cardHandIn
    * @param array<mixed> $currentDeckIn
    * @param string $whoIn
    * @return array<mixed>
    */
    public function firstCardDraw(
        CardHand $cardHandIn,
        array $currentDeckIn,
        string $whoIn,
    ): array {
        $cardHand = $cardHandIn;
        $currentDeck = $currentDeckIn;
        $currentPlayer = $whoIn;

        /** @var array<CardGraphic> $currentDeck*/
        $drawnCard = array_pop($currentDeck);
        /** @var CardGraphic $drawnCard*/
        $cardHand->add($drawnCard);
        /** @var CardGraphic $drawnCard*/
        $lastPlayerCard = $drawnCard->getTextAsString();
        //}

        $currentHandScore = $this->sumCurrentHand($cardHand);

        $data = [
            "current_deck" => $currentDeck,
            $currentPlayer . "_hand" => $cardHand,
            "last_" . $currentPlayer . "_card" => $lastPlayerCard,
            $currentPlayer . "_hand_score" => $currentHandScore
            // "cardsLeft" => (is_countable($shuffledDeck)) ? count($shuffledDeck) : ''
        ];

        return $data;
    }

    /**
    * Takes cardHand and sums the score(s).
    * @param CardHand $cardHandIn
    * @return array<int>
    */
    public function sumCurrentHand(CardHand $cardHandIn): array
    {
        $convertedToNumbers = [
            'Ace' => 1, 'Two' => 2, 'Three' => 3, 'Four' => 4,
            'Five' => 5, 'Six' => 6, 'Seven' => 7,
            'Eight' => 8, 'Nine' => 9, 'Ten' => 10,
            'Jack' => 10, 'Queen' => 10,
            'King' => 10];

        $cardHandasArray = $cardHandIn->getAsArray();
        $handAsNumbers = [];
        foreach ($cardHandasArray as $x) {
            $tmpArray = explode(" ", $x->getTextAsString());

            array_push($handAsNumbers, $convertedToNumbers[$tmpArray[0]]);

        }
        $handSum = array_sum($handAsNumbers);
        // $handSum2 = 0;
        $toReturn = [$handSum];

        if (in_array(1, $handAsNumbers)) {
            /*
            function array_count_values_of(1, $handAsNumbers) {
                $counts = array_count_values($array);
                return $counts[$value];
            } */
            $handSum2 = $handSum + 10;
            $toReturn = [$handSum, $handSum2];
        }

        return $toReturn;
    }
}
