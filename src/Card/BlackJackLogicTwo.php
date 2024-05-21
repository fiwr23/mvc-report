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
* BlackJackLogicTwo class, contains game functions.
*/
class BlackJackLogicTwo
{
    /**
    * Resets everything for new game.
    * @param string $playerName
    * @param string|int $numCardHands
    * @param array<int> $allBets
    * @param array<mixed>|null $currentDeck
    * @return array<mixed>
    */
    public function newGame(
        string $playerName,
        string|int $numCardHands,
        array $allBets,
        array|null $currentDeck = null
    ): array {

        $shuffledDeck = $currentDeck;
        if (!$currentDeck || count($currentDeck) < $numCardHands) {
            $deck = new DeckOfCards();
            $shuffledDeck = $deck->getAllCardsShuffled();
        }
        // else {
        //    $shuffledDeck = $currentDeck;
        // }

        // $playerHand = new CardHand();
        $bankHand = new CardHand();

        $gameLogic = new BlackJackLogicThree();

        $playerSpotTwoData = [];
        $playerSpotThreeData = [];

        // $newPlayerData = $gameLogic->firstCardDraw($playerHand, $shuffledDeck, 'player');
        /**
         * @var array<CardGraphic> $shuffledDeck
         */
        $newPlayerData = $this->populateSpotData($shuffledDeck, $allBets[0]);
        $shuffledDeck = $newPlayerData['current_deck'];
        $playerSpotOneData = $newPlayerData['spotData'];

        $playerSpotTwoData = [];

        if ($numCardHands > 1) {
            /**
            * @var array<CardGraphic> $shuffledDeck
            */
            $newPlayerData = $this->populateSpotData($shuffledDeck, $allBets[1]);
            $shuffledDeck = $newPlayerData['current_deck'];
            $playerSpotTwoData = $newPlayerData['spotData'];
        }

        $playerSpotThreeData = [];

        if ($numCardHands == 3) {
            /**
             * @var array<CardGraphic> $shuffledDeck
            */
            $newPlayerData = $this->populateSpotData($shuffledDeck, $allBets[2]);
            $shuffledDeck = $newPlayerData['current_deck'];
            $playerSpotThreeData = $newPlayerData['spotData'];
        }
        // $newPlayerData = $gameLogic->firstCardDraw($playerHand, $shuffledDeck, 'player');
        // $shuffledDeck = $newPlayerData['current_deck'];
        /**
        * @var array<CardGraphic> $shuffledDeck
        */
        $newBankData = $gameLogic->firstCardDraw($bankHand, $shuffledDeck, 'bank');
        $shuffledDeck = $newBankData['current_deck'];
        /* $playerSpotOneData = [
            'spot_player_hand' => $newPlayerData['player_hand'],
            'spot_player_bet' => 0,
            'spot_last_player_card' => $newPlayerData['last_player_card'],
            'spot_player_hand_score' => $newPlayerData['player_hand_score']
        ]; */

        // $newBankData = $gameLogic->firstCardDraw($bankHand, $shuffledDeck, 'bank');
        // $shuffledDeck = $newBankData['current_deck'];

        $data = [
            'current_deck' => $shuffledDeck,
            'current_player' => 'player',
            'player_name' => $playerName,
            // 'player_hand' => $newPlayerData['player_hand'],
            // 'spot_one_player_hand' => $playerSpotOneData['spot_player_hand'],
            'bank_hand' => $newBankData['bank_hand'],
            // "last_player_card" => $newPlayerData['last_player_card'],
            "last_bank_card" => $newBankData['last_bank_card'],
            // 'player_hand_score' => $newPlayerData['player_hand_score'],
            'bank_hand_score' => $newBankData['bank_hand_score'],
            // 'player_wins' => 0,
            // 'bank_wins' => 0,
            'game_state' => 'ongoing',
            'player_spot_one' => $playerSpotOneData,
            'player_spot_two' => $playerSpotTwoData,
            'player_spot_three' => $playerSpotThreeData
        ];
        return $data;
    }

    /**
    * Populate spot data.
    * @param array<mixed> $shuffledDeck
    * @param int $bet
    * @return array<mixed>
    */
    public function populateSpotData(array $shuffledDeck, int $bet): array
    {
        $playerHand = new CardHand();
        $gameLogic = new BlackJackLogicThree();

        $newPlayerData = $gameLogic->firstCardDraw($playerHand, $shuffledDeck, 'player');

        $newPlayerData['spotData'] = [
            'spot_player_hand' => $newPlayerData['player_hand'],
            'spot_player_bet' => $bet,
            'spot_state' => 'started',
            'spot_last_player_card' => $newPlayerData['last_player_card'],
            'spot_player_hand_score' => $newPlayerData['player_hand_score']
        ];

        return $newPlayerData;
    }

    /**
    * Resets everything for next round.
    *
    * @return array<mixed>
    */
    public function nextRound(): array
    {
        $bankHand = new CardHand();

        $data = [
            // 'current_deck' => $shuffledDeck,
            'current_player' => 'player',
            'bank_hand' => $bankHand,
            "last_bank_card" => "-",
            'bank_hand_score' => [0],
            'game_state' => 'ongoing'

        ];
        return $data;
    }

    /**
     * Returns player's cardhand score(s) as string.
    * @param array<int> $arrayIn
    * @return string
    */
    public function scoreString(array $arrayIn): string
    {
        $tmpArray = $arrayIn;
        switch(sizeof($tmpArray) > 1) {
            case true:
                $tmp = "";
                $arrSize = sizeof($tmpArray);
                for ($i = 0; $i < $arrSize; $i++) {
                    $tmp = $tmp . $tmpArray[$i];
                    if (($i + 1) !== sizeof($tmpArray)) {
                        $tmp = $tmp . " or ";
                    }
                }
                $tmpArray = $tmp;
                break;
            default:
                $tmpArray = $tmpArray[0];
        }
        return $tmpArray . "";
    }
}
