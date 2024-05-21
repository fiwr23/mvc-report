<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogicThree;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* BlackJackLogicFive class, contains game functions.
*/
class BlackJackLogicFive
{
    /**
    * Hit method.
    * @param int $money
    * @param array<mixed> $currentDeckIn
    * @param array<mixed> $playerSpot
    * @param string $activeSpot
    * @param array<mixed> $playerSpotOne
    * @param array<mixed>|null $playerSpotTwo
    * @param array<mixed>|null $playerSpotThree
    * @return array<mixed>
    */
    public function hit(
        int $money,
        array $currentDeckIn,
        array $playerSpot,
        string $activeSpot,
        array $playerSpotOne,
        array|null $playerSpotTwo = null,
        array|null $playerSpotThree = null
    ): array {
        $currentDeck = $currentDeckIn;
        $gameLogic = new BlackJackLogicThree();
        /** @var CardHand $plrSpothand*/
        $plrSpothand = $playerSpot["spot_player_hand"];
        $spotNewData = $gameLogic->firstCardDraw(
            $plrSpothand,
            $currentDeck,
            'player'
        );
        $currentDeck = $spotNewData['current_deck'];
        $playerSpot['spot_player_hand'] = $spotNewData['player_hand'];

        $playerSpot['spot_last_player_card'] = $spotNewData['last_player_card'];
        $playerSpot['spot_player_hand_score'] = $spotNewData['player_hand_score'];
        /**
         * @var array<array<int>> $playerSpot
         */
        $spPlHScore = $playerSpot['spot_player_hand_score'];
        /**
         * @var array<int> $spPlHScore
         */
        $spotNewState = $this->checkIfGameOver($spPlHScore);
        if ($spotNewState === 'win') {
            $playerSpot['spot_state'] = 'won';
            $money = $money + (int)$playerSpot['spot_player_bet'] * 2;
        }

        if ($spotNewState === 'loss') {
            $playerSpot['spot_state'] = 'loss';
        }

        $data = [
            'current_deck' => $currentDeck,
            'money' => $money,
            $activeSpot => $playerSpot
        ];

        if ($playerSpotTwo == null) {
            $playerSpotTwo = [
                'spot_state' => 'not_active'
            ];
        }

        if ($playerSpotThree == null) {
            $playerSpotThree = [
                'spot_state' => 'not_active'
            ];
        }

        // No active spot anymore
        if (in_array('started', [$playerSpotOne['spot_state'],
            $playerSpotTwo['spot_state'], $playerSpotThree['spot_state']]) === false &&
            in_array('stay', [$playerSpotOne['spot_state'],
            $playerSpotTwo['spot_state'], $playerSpotThree['spot_state']]) === false) {
            // next round button will show
            $data['game_state'] = 'finished';
        }

        return $data;

    }

    /**
    * Checks if spot's game is over.
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
    * Checks if all spots have either won or lost.
    *
    * @param array<mixed> $playerSpotOne
    * @param array<mixed>|null $playerSpotTwo
    * @param array<mixed>|null $playerSpotThree
    * @return string
    */
    public function allSpotsWonOrLost(
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

        if (in_array('started', $allSpotStates) === false &&
        in_array('stay', $allSpotStates) === false) {
            return 'yes';
        }
        return 'no';
    }
}
