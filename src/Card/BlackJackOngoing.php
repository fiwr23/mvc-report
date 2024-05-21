<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogicTwo;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* BlackJackOngoing class, contains function for ongoing route.
*/
class BlackJackOngoing
{
    /**
    * Ongoing data creator.
    * @param string $gameState
    * @param int $money
    * @param string $lastBankCard
    * @param CardHand $bankHand
    * @param array<int> $bankHandScore
    * @param string $currentPlayer
    * @param string $playerName
    * @param array<mixed> $someData
    * @return array<mixed>
    */
    public function ongoingDataCreator(
        string $gameState,
        int $money,
        string $lastBankCard,
        CardHand $bankHand,
        // array $playerHandScore,
        array $bankHandScore,
        string $currentPlayer,
        string $playerName,
        array $someData
    ): array {
        $gameLogic = new BlackJackLogicTwo();
        $bet1 = $someData['bet1'];
        $bet2 = $someData['bet2'];
        $bet3 = $someData['bet3'];
        $playerSpotOne = $someData['player_spot_one'];
        $playerSpotTwo = $someData['player_spot_two'];
        $playerSpotThree = $someData['player_spot_three'];
        $numCardHands = $someData['num_card_hands'];

        /**
         * @var array<int> $bankHandScore
         *
        */
        $bankHandScoreStr = $gameLogic->scoreString($bankHandScore);

        /**
         * @var array<mixed> $playerSpotOne
         * @var array<array<int>> $playerSpotOne['spot_player_hand_score']
        */
        $playerSpotOne['spot_hand_score'] =
            $gameLogic->scoreString($playerSpotOne['spot_player_hand_score']);

        $spotTwoAsArray = [new CardGraphic("0", "0")];
        $spotThreeAsArray = [new CardGraphic("0", "0")];

        if ($numCardHands > 1) {
            /**
             * @var array<mixed> $playerSpotTwo
             */
            $spotPlayerTwoHscore = $playerSpotTwo['spot_player_hand_score'];
            $spotPlayerTwoHand = $playerSpotTwo['spot_player_hand'];
            /**
             * @var array<int> $spotPlayerTwoHscore
            */
            $playerSpotTwo['spot_hand_score'] =
                $gameLogic->scoreString($spotPlayerTwoHscore);
            /**
            * @var CardHand $spotPlayerTwoHand
            */
            $spotTwoAsArray = $spotPlayerTwoHand->getAsArray();
        }

        if ($numCardHands == 3) {
            /**
             * @var array<mixed> $playerSpotThree
             */
            $spotPlyerThreeHscore = $playerSpotThree['spot_player_hand_score'];
            $spotPlayerThreeHand = $playerSpotThree['spot_player_hand'];
            /**
             * @var array<int> $spotPlyerThreeHscore
            */
            $playerSpotThree['spot_hand_score'] =
            $gameLogic->scoreString($spotPlyerThreeHscore);
            /**
            * @var CardHand $spotPlayerThreeHand
            */
            $spotThreeAsArray = $spotPlayerThreeHand->getAsArray();
        }
        /**
         * @var CardHand $bankHand
         * @var array<CardHand> $playerSpotOne['spot_player_hand']
        */
        $spotOneHand = $playerSpotOne['spot_player_hand'];
        $data = [
            "bank_hand" => $bankHand->getAsArray(),
            "last_bank_card" => $lastBankCard,
            "bank_hand_score" => $bankHandScoreStr,
            "current_player" => $currentPlayer,
            "player_name" => $playerName,
            "game_state" => $gameState,
            "money" => $money,
            "player_spot_one" => $playerSpotOne,
            "player_spot_two" => $playerSpotTwo,
            "player_spot_three" => $playerSpotThree,
            /**
            * @var CardHand $spotOneHand
            */
            'spot_one_player_hand' => $spotOneHand->getAsArray(),
            "spot_two_player_hand" => $spotTwoAsArray,
            "spot_three_player_hand" => $spotThreeAsArray,
            "bet1" => $bet1,
            "bet2" => $bet2,
            "bet3" => $bet3,
            "num_card_hands" => $numCardHands
        ];

        return $data;
    }
}
