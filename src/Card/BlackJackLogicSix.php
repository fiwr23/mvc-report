<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogicThree;
use App\Card\BlackJackLogicSeven;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* BlackJackLogicSix class, contains game functions.
*/
class BlackJackLogicSix
{
    /**
    * Checks if all spots are done and it is banks turn.
    * @param array<mixed> $playerSpotOne
    * @param array<mixed>|null $playerSpotTwo
    * @param array<mixed>|null $playerSpotThree
    * @return string
    */
    public function isItBanksTurn(
        $playerSpotOne,
        array|null $playerSpotTwo = null,
        array|null $playerSpotThree = null
    ) {

        if ($playerSpotTwo == null) {
            $playerSpotTwo =
                ['spot_state' => 'not_active'];
        }

        if ($playerSpotThree == null) {
            $playerSpotThree =
                ['spot_state' => 'not_active'];
        }
        $allSpotStates = [
            $playerSpotOne['spot_state'], $playerSpotTwo['spot_state'], $playerSpotThree['spot_state']];

        if (in_array('started', $allSpotStates)) {
            return 'no';
        }
        return 'yes';
    }

    /**
    * Bank's turn.
    * @param int $money
    * @param array<mixed> $currentDeck
    * @param CardHand $bankHand
    * @param array<mixed> $playerSpotOne
    * @param array<mixed>|null $playerSpotTwo
    * @param array<mixed>|null $playerSpotThree
    * @return array<mixed>
    */
    public function banksTurn(
        int $money,
        array $currentDeck,
        CardHand $bankHand,
        array $playerSpotOne,
        array|null $playerSpotTwo = null,
        array|null $playerSpotThree = null
    ): array {
        if ($playerSpotTwo == null) {// If null spot not used
            $playerSpotTwo = [
                'spot_state' => 'not_active'
            ];
        }

        if ($playerSpotThree == null) {
            $playerSpotThree = [
                'spot_state' => 'not_active'
            ];
        }

        //find all spots with stay
        $allSpots = [$playerSpotOne, $playerSpotTwo, $playerSpotThree];
        $allStaySpots = array_filter($allSpots, function ($element) {
            if ($element['spot_state'] === 'stay') {
                return $element;
            }
        });
        // no stay equals all spots are either won or loss bank doesn't have to do anything
        if (count($allStaySpots) === 0) {
            // next round button will show
            return ['game_state' => 'finished'];
        }

        // get banks handscore first
        $bankHandScore = $this->sumCurrentHand($bankHand);

        // if bank is below 17 draw new card
        $validScores = $this->validScore($bankHandScore);

        // no valid scores between 17 and 21 so lets see if <17
        $toStore = [];
        if (!is_countable($validScores) || count($validScores) === 0) {
            // find values below 17
            $tooLowScores = array_filter($bankHandScore, function ($element) {
                if ($element < 17) {
                    return $element;
                }
            });
            // if value below 17 draw new card
            if (count($tooLowScores) > 0) {
                // case true:
                $gameLogic = new BlackJackLogicThree();
                $toStore = $gameLogic->firstCardDraw($bankHand, $currentDeck, 'bank');
                $bankHandScore = $toStore['bank_hand_score'];
                $bankHand = $toStore['bank_hand'];
                $currentDeck = $toStore['current_deck'];
                /**
                 * @var array<int|null> $bankHandScore
                 */
                $validScores = $this->validScore($bankHandScore);
                // no valid score or 21 after new card = return
                $gameLogic = new BlackJackLogicSeven();
                /**
                 * @var CardHand $bankHand
                 */
                $moreToStore = $gameLogic->checkBankAndSpotScores(
                    $money,
                    $bankHand,
                    $playerSpotOne,
                    $playerSpotTwo,
                    $playerSpotThree
                );
                $allToStore = array_replace($toStore, $moreToStore);
                return $allToStore;
            }
        }

        $toStore['current_deck'] = $currentDeck;
        return $toStore;
    }

    /**
    * Returns valid scores in array.
    * @param array<int|null> $arrayIn
    * @return array<int|null>
    */
    public function validScore(array $arrayIn): array
    {
        $tmpArray = [];
        foreach ($arrayIn as $element) {
            if ($element >= 17 && $element <= 21) {
                array_push($tmpArray, $element);
            }
        }

        return $tmpArray;
    }

    /**
    * Compare two arrays and return winner.
    * @param int $money
    * @param int $bankHighest
    * @param array<mixed> $theSpot
    * @return array<mixed>
    */
    public function compareHands($money, int $bankHighest, array $theSpot)
    {

        // first valid scores
        if ($theSpot['spot_state'] != 'stay') {
            return [
                'the_spot' => $theSpot,
                'money' => $money
            ];
        }
        $bestStaySpotScore = array_filter(
            $theSpot['spot_player_hand_score'],
            function ($element) {
                if ($element < 21) {
                    return $element;
                }
            }
        );
        // then highest of the valid scores
        $bestSpotSingleScore = max($bestStaySpotScore);
        // compare with best valid bank score
        /* if ($bestSpotSingleScore > $bankHighest) {
            $theSpot['spot_state'] = 'won';
            return [
                'the_spot' => $theSpot,
                'money' => $money + (int)$theSpot['spot_player_bet'] * 2
            ];
        } else {
            $theSpot['spot_state'] = 'loss';
            return [
                'the_spot' => $theSpot,
                'money' => $money
            ];
        } */

        switch ($bestSpotSingleScore > $bankHighest) {
            case true:
                $theSpot['spot_state'] = 'won';
                return [
                    'the_spot' => $theSpot,
                    'money' => $money + (int)$theSpot['spot_player_bet'] * 2
                ];
            default:
                $theSpot['spot_state'] = 'loss';
                return [
                    'the_spot' => $theSpot,
                    'money' => $money
                ];
        }
    }

    /**
    * Checks if bank's game is over.
    *
    * @param array<int> $sumArrayIn
    * @return string
    */
    public function checkIfGameOver(
        array $sumArrayIn,
    ): string {
        $tmpState = "loss"; // will change to ongoing if one sum is below 21

        $currScoreArraySize = sizeof($sumArrayIn);
        for ($i = 0; $i < $currScoreArraySize; $i++) {
            if ($sumArrayIn[$i] === 21) {
                return "win";
            }

            if ($sumArrayIn[$i] < 21) {
                $tmpState = "ongoing";
            }
        }

        return $tmpState;
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
