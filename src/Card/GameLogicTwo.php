<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* GameLogic class two, contains game functions.
*/
class GameLogicTwo
{
    /**
     * Resets everything for new game.
     *
    * @return array<mixed>
    */
    public function newGame(): array
    {
        $deck = new DeckOfCards();
        $shuffledDeck = $deck->getAllCardsShuffled();
        $playerHand = new CardHand();
        $bankHand = new CardHand();

        $data = [
            'current_deck' => $shuffledDeck,
            'current_player' => 'player',
            'player_hand' => $playerHand,
            'bank_hand' => $bankHand,
            "last_player_card" => "-",
            "last_bank_card" => "-",
            'player_hand_score' => [0],
            'bank_hand_score' => [0],
            'player_wins' => 0,
            'bank_wins' => 0,
            'game_state' => 'ongoing'

        ];
        return $data;
    }

    /**
    * Resets everything for next round, Wins/losses not affected.
    *
    * @return array<mixed>
    */
    public function nextRound(): array
    {
        // $deck = new DeckOfCards();
        // $shuffledDeck = $deck->getAllCardsShuffled();
        $playerHand = new CardHand();
        $bankHand = new CardHand();

        $data = [
            // 'current_deck' => $shuffledDeck,
            'current_player' => 'player',
            'player_hand' => $playerHand,
            'bank_hand' => $bankHand,
            "last_player_card" => "-",
            "last_bank_card" => "-",
            'player_hand_score' => [0],
            'bank_hand_score' => [0],
            // 'player_wins' => 0,
            // 'bank_wins' => 0,
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
