<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogicThree;
use App\Card\BlackJackLogicSix;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* BlackJackLogicSeven class, contains game functions.
*/
class BlackJackLogicSeven
{
    /**
    * Checks if bank has 17-20 or 21 or >21
    * @param int $money
    * @param CardHand $bankHand
    * @param array<mixed> $playerSpotOne
    * @param array<mixed>|null $playerSpotTwo
    * @param array<mixed>|null $playerSpotThree
    * @return array<mixed>
    */
    public function checkBankAndSpotScores(
        int $money,
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

        $toStore = [];
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
        $sumLogic = new BlackJackLogicSix();
        $bankHandScore = $sumLogic->sumCurrentHand($bankHand);

        // if bank got 21 its game over all stay should be loss
        if (in_array(21, $bankHandScore)) {
            $toStore = $this->stayToLoss($playerSpotOne, $playerSpotTwo, $playerSpotThree);
            return $toStore;
        }
        // check if bank scores
        $validScores = $sumLogic->validScore($bankHandScore);

        // no valid scores between 17 and 21 so lets see if <17
        if (!is_countable($validScores) || count($validScores) === 0) {
            // find values below 17
            $below17Data = $this->below17ElseSpotsWins(
                $money,
                $bankHandScore,
                $playerSpotOne,
                $playerSpotTwo,
                $playerSpotThree
            );
            if ($below17Data) { // Recieved new data which means no value below 17 and spots won.
                return $below17Data;
            }
        }
        /**
        * @var array<int> $validScores
        */
        $newToStore = $this->validBankScoreComp(
            $money,
            $validScores,
            $playerSpotOne,
            $playerSpotTwo,
            $playerSpotThree
        );
        $toStore = array_replace($toStore, $newToStore);
        return $toStore;
    }

    /**
    * When bank has 21. Changes spots stay to loss
    * @param array<mixed> $playerSpotOne
    * @param array<mixed> $playerSpotTwo
    * @param array<mixed> $playerSpotThree
    * @return array<mixed>
    */
    public function stayToLoss(
        array $playerSpotOne,
        array $playerSpotTwo,
        array $playerSpotThree
    ): array {
        $toStore = [];
        if ($playerSpotOne['spot_state'] === 'stay') {
            $playerSpotOne['spot_state'] = 'loss';
            $toStore["player_spot_one"] = $playerSpotOne;
        }

        if ($playerSpotTwo['spot_state'] === 'stay') {
            $playerSpotTwo['spot_state'] = 'loss';
            $toStore["player_spot_two"] = $playerSpotTwo;
        }

        if ($playerSpotThree['spot_state'] === 'stay') {
            $playerSpotThree['spot_state'] = 'loss';
            $toStore["player_spot_three"] = $playerSpotThree;
        }
        $toStore['game_state'] = 'finished';
        return $toStore;
    }

    /**
    * Check if bank scores below 17, if not return spots with wins.
    * @param int $money
    * @param array<int> $bankHandScore
    * @param array<mixed> $playerSpotOne
    * @param array<mixed> $playerSpotTwo
    * @param array<mixed> $playerSpotThree
    * @return array<mixed>
    */
    public function below17ElseSpotsWins(
        int $money,
        array $bankHandScore,
        array $playerSpotOne,
        array $playerSpotTwo,
        array $playerSpotThree
    ): array {
        $toStore = [];
        $tooLowScores = array_filter($bankHandScore, function ($element) {
            if ($element < 17) {
                return $element;
            }
        });
        // if no value below 17 bank has lost
        if (count($tooLowScores) === 0) {
            // all bank scores over 21 = all stay spots win
            if ($playerSpotOne['spot_state'] === 'stay') {
                $playerSpotOne['spot_state'] = 'won';
                $toStore["player_spot_one"] = $playerSpotOne;
                $money = $money + $playerSpotOne["spot_player_bet"] * 2;
            }

            if ($playerSpotTwo['spot_state'] === 'stay') {
                $playerSpotTwo['spot_state'] = 'won';
                $toStore["player_spot_two"] = $playerSpotTwo;
                $money = $money + $playerSpotTwo["spot_player_bet"] * 2;
            }

            if ($playerSpotThree['spot_state'] === 'stay') {
                $playerSpotThree['spot_state'] = 'won';
                $toStore["player_spot_three"] = $playerSpotThree;
                $money = $money + $playerSpotThree["spot_player_bet"] * 2;
            }
            $toStore["money"] = $money;
            $toStore['game_state'] = 'finished';
            return $toStore;
        }
        return $toStore;
    }

    /**
    * Bank has valid score, compare to spots.
    * @param int $money
    * @param array<int> $validScores
    * @param array<mixed> $playerSpotOne
    * @param array<mixed> $playerSpotTwo
    * @param array<mixed> $playerSpotThree
    * @return array<mixed>
    */
    public function validBankScoreComp(
        int $money,
        array $validScores,
        array $playerSpotOne,
        array $playerSpotTwo,
        array $playerSpotThree
    ): array {
        $toStore = [];
        if (!empty($validScores)) {
            $sumLogic = new BlackJackLogicSix();
            $bankHighest = max($validScores);
            // compare valid bank score with each spot's stay score
            /**
             * @var int $money
             */
            $comparedSpotOneData = $sumLogic->compareHands($money, $bankHighest, $playerSpotOne);
            $money = $comparedSpotOneData['money'];
            /**
             * @var int $money
             */
            $comparedSpotTwoData = $sumLogic->compareHands($money, $bankHighest, $playerSpotTwo);
            $money = $comparedSpotTwoData['money'];
            /**
             * @var int $money
             */
            $compSpotThreeData = $sumLogic->compareHands($money, $bankHighest, $playerSpotThree);
            $money = $compSpotThreeData['money'];
            $toStore["player_spot_one"] = $comparedSpotOneData['the_spot'];
            $toStore["player_spot_two"] = $comparedSpotTwoData['the_spot'];
            $toStore["player_spot_three"] = $compSpotThreeData['the_spot'];
            $toStore['money'] = $money;
            $toStore['game_state'] = 'finished';
        }
        return $toStore;
    }
}
