<?php

namespace App\Card;

use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* GameLogic class, contains most game functions.
*/
class GameLogic
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
     * Draws one card then checks game state.
     *
    * @param  array<CardGraphic> $currentDeckIn
    * @return array<mixed>
    */

    public function drawOneCard(
        CardHand $cardHandIn,
        array $currentDeckIn,
        string $whoIn,
        CardHand $opponentHandIn,
        int $playerWinsIn,
        int $bankWinsIn
    ): array {
        $cardHand = $cardHandIn;
        $opponentHand = $opponentHandIn;
        $currentDeck = $currentDeckIn;
        $currentPlayer = $whoIn;
        $playerWins = $playerWinsIn;
        $bankWins = $bankWinsIn;


        if (is_array($currentDeck)) {
            /** @var array<CardGraphic> $currentDeck*/
            $drawnCard = array_pop($currentDeck);
            /** @var CardGraphic $drawnCard*/
            $cardHand->add($drawnCard);
            /** @var CardGraphic $drawnCard*/
            $lastPlayerCard = $drawnCard->getTextAsString();
        }

        $currentHandScore = $this->sumCurrentHand($cardHand);
        $opponentHandScore = $this->sumCurrentHand($opponentHand);
        $gameState = $this->checkIfGameOver($currentHandScore, $currentPlayer, $opponentHandScore);

        if ($gameState === "win") {
            switch ($currentPlayer) {
                case 'bank':
                    $bankWins += 1;
                    break;
                case 'player':
                    $playerWins += 1;
            }

        }
        if ($gameState === "loss") {
            switch ($currentPlayer) {
                case 'bank':
                    $playerWins += 1;
                    break;
                case 'player':
                    $bankWins += 1;
            }

        }
        $data = [
            "current_deck" => $currentDeck,
            $currentPlayer . "_hand" => $cardHand,
            "last_" . $currentPlayer . "_card" => $lastPlayerCard,
            $currentPlayer . "_hand_score" => $currentHandScore,
            "game_state" => $gameState,
            "player_wins" => $playerWins,
            "bank_wins" => $bankWins

            // "cardsLeft" => (is_countable($shuffledDeck)) ? count($shuffledDeck) : ''
        ];

        return $data;
    }

    /**
    * Takes cardHand and sums the score(s).
    *
    * @return array<int>
    */
    public function sumCurrentHand(CardHand $cardHandIn): array
    {
        $convertedToNumbers = [
            'Ace' => 1, 'Two' => 2, 'Three' => 3, 'Four' => 4,
            'Five' => 5, 'Six' => 6, 'Seven' => 7,
            'Eight' => 8, 'Nine' => 9, 'Ten' => 10,
            'Knight' => 11, 'Queen' => 12,
            'King' => 13];

        $cardHandasArray = $cardHandIn->getAsArray();
        $handAsNumbers = [];
        foreach ($cardHandasArray as $x) {
            $tmpArray = explode(" ", $x->getTextAsString());

            array_push($handAsNumbers, $convertedToNumbers[$tmpArray[0]]);

        }
        $handSum = array_sum($handAsNumbers);
        $handSum2 = 0;
        $toReturn = [$handSum];

        if (in_array(1, $handAsNumbers)) {
            /*
            function array_count_values_of(1, $handAsNumbers) {
                $counts = array_count_values($array);
                return $counts[$value];
            } */
            $handSum2 = $handSum + 13;
            $toReturn = [$handSum, $handSum2];
        }

        return $toReturn;
    }

    /**
    * Checks if game is over.
    *
    * @param array<int> $sumArrayIn
    * @param array<int> $opponentSumArrayIn
    * @return string
    */
    public function checkIfGameOver(
        array $sumArrayIn,
        string $whoIn,
        array $opponentSumArrayIn
    ): string {
        $tmpState = "loss"; // will change to ongoing if one sum is below 21
        $aboveStayValue = 0;
        $currScoreArraySize = sizeof($sumArrayIn);
        for ($i = 0; $i < $currScoreArraySize; $i++) {
            if ($sumArrayIn[$i] === 21) {
                return "win";
            }

            if ($sumArrayIn[$i] < 21) {
                $tmpState = "ongoing";
                if ($sumArrayIn[$i] >= 17 && $sumArrayIn[$i] > $aboveStayValue) {
                    $aboveStayValue = $sumArrayIn[$i];
                }
            }
        }

        if($tmpState === "loss") {
            return $tmpState;
        }

        $tmpState = $this->lowerComplexity($whoIn, $aboveStayValue, $opponentSumArrayIn);
        return $tmpState;
    }

    /**
    * If current player is bank then stop if sum over 17.
    *
    * @param array<int> $opponentSumArrayIn
    * @return string
    */
    public function lowerComplexity(string $whoIn, int $aboveStayValue, array $opponentSumArrayIn): string
    {
        // lower score above 21 in opponents score list so max() works
        $oppentScoreArraySize = sizeof($opponentSumArrayIn);
        for ($i = 0; $i < $oppentScoreArraySize; $i++) {
            if ($opponentSumArrayIn[$i] > 21) {
                $opponentSumArrayIn[$i] = 0;
            }
        }

        if ($aboveStayValue >= 17 && $whoIn === 'bank') { // bank stays
            if ($aboveStayValue >= max($opponentSumArrayIn)) {
                return "win";
            }
            return "loss";
        }

        return "ongoing";
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
