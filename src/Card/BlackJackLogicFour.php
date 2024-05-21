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
* BlackJackLogicFour class, contains game functions.
*/
class BlackJackLogicFour
{
    /**
    * Second round of cards method.
    * @param int $money
    * @param array<CardGraphic> $currentDeckIn
    * @param array<mixed> $playerSpotOne
    * @param array<mixed> $playerSpotTwo
    * @param array<mixed> $playerSpotThree
    * @return array<mixed>
    */
    public function secondRoundOfCards(
        CardHand $bankHand,
        int $money,
        array $currentDeckIn,
        array $playerSpotOne,
        array|null  $playerSpotTwo = null,
        array|null  $playerSpotThree = null
    ): array {
        $currentDeck = $currentDeckIn;
        $gameLogic = new BlackJackLogicThree();
        $spPOneHand = $playerSpotOne["spot_player_hand"];
        /**
         * @var CardHand $spPOneHand
         */
        $spotOneNewData = $gameLogic->firstCardDraw(
            $spPOneHand,
            $currentDeck,
            'player'
        );
        $currentDeck = $spotOneNewData['current_deck'];
        $playerSpotOne['spot_player_hand'] = $spotOneNewData['player_hand'];

        $playerSpotOne['spot_last_player_card'] = $spotOneNewData['last_player_card'];
        $playerSpotOne['spot_player_hand_score'] = $spotOneNewData['player_hand_score'];

        $spPlHanS = $playerSpotOne['spot_player_hand_score'];
        /**
         * @var array<int> $spPlHanS
         */
        $spotNewState = $this->checkIfGameOver($spPlHanS);
        if ($spotNewState == 'win') {
            $playerSpotOne['spot_state'] = 'won';
            $money = $money + $playerSpotOne['spot_player_bet'] * 2;
        }

        if ($spotNewState == 'loss') {
            $playerSpotOne['spot_state'] = 'loss';
        }

        /**
         * @var array<CardGraphic> $currentDeck
         */
        $spotTwoNewData = $this->newCardSpot($playerSpotTwo, $currentDeck, $money);

        $money = $spotTwoNewData['money'];
        $currentDeck = $spotTwoNewData['current_deck'];
        $playerSpotTwo = $spotTwoNewData['player_spot'];
        /**
         * @var int $money
         * @var array<CardGraphic> $currentDeck
         */
        $spotThreeNewData =  $this->newCardSpot($playerSpotThree, $currentDeck, $money);

        $money = $spotThreeNewData['money'];
        $currentDeck = $spotThreeNewData['current_deck'];
        $playerSpotThree = $spotThreeNewData['player_spot'];

        /**
         * @var array<CardGraphic> $currentDeck
         */
        $bankNewData = $gameLogic->firstCardDraw(
            $bankHand,
            $currentDeck,
            'bank'
        );
        $currentDeck = $bankNewData['current_deck'];

        $data = [
            'current_deck' => $currentDeck,
            'money' => $money,
            'bank_hand' => $bankNewData['bank_hand'],
            'player_spot_one' => $playerSpotOne,
            'player_spot_two' => $playerSpotTwo,
            'player_spot_three' => $playerSpotThree
        ];

        if ($playerSpotTwo == null) {
            // $playerSpotTwo = [];
            $playerSpotTwo = [
                'spot_state' => 'not_active'
            ];
        }

        if ($playerSpotThree == null) {
            // $playerSpotThree = [];
            $playerSpotThree = [
                'spot_state' => 'not_active'
            ];
        }

        // No active spot anymore
        /**
         * @var array<string> $playerSpotOne
         * @var array<string> $playerSpotTwo
         * @var array<string> $playerSpotThree
         */
        $spStateOne = $playerSpotOne['spot_state'];
        $spStateTwo = $playerSpotTwo['spot_state'];
        $spStateThree = $playerSpotThree['spot_state'];
        /**
         * @var string $spStateOne
         * @var string $spStateTwo
         * @var string $spStateThree
         */
        if (in_array('started', [$spStateOne,$spStateTwo,$spStateThree]) === false) {
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
    * New drawn card spot if not null.
    * @param array<mixed>|null $playerSpot
    * @param array<CardGraphic> $currentDeck
    * @param int $money
    * @return array<mixed>
    */
    public function newCardSpot(
        array|null $playerSpot,
        array $currentDeck,
        int $money
    ): array {

        $gameLogic = new BlackJackLogicThree();

        if ($playerSpot !== null) {
            $spPlHand = $playerSpot["spot_player_hand"];
            /**
             * @var CardHand $spPlHand
             */
            $spotNewData = $gameLogic->firstCardDraw(
                $spPlHand,
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
            $spotPlyrHScore = $playerSpot['spot_player_hand_score'];
            $spotNewState = $this->checkIfGameOver($spotPlyrHScore);
            if ($spotNewState == 'win') {
                $playerSpot['spot_state'] = 'won';
                /**
                 * @var array<int> $playerSpot
                 */
                $money = $money + $playerSpot['spot_player_bet'] * 2;
            }

            if ($spotNewState == 'loss') {
                $playerSpot['spot_state'] = 'loss';
            }
        }
        return [
            'money' => $money,
            'current_deck' => $currentDeck,
            'player_spot' => $playerSpot
        ];
    }
}
