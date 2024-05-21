<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogic;
use App\Card\BlackJackLogicTwo;
use App\Card\BlackJackOngoing;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Black Jack controller two.
 */
class BlackJackControllerTwo extends AbstractController
{
    #[Route("/proj/play", name: "play_game_black_jack")]
    public function blackJackPlay(SessionInterface $session): Response
    {
        $numCardHands = $session->get("num_card_hands");
        $currentDeck = $session->get('current_deck');
        if (is_countable($currentDeck) && count($currentDeck) < ($numCardHands + 1)) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by deleting session'
            );
            return $this->redirectToRoute('ongoing_black_jack');
        }
        $gameState = $session->get("game_state");
        switch ($gameState === 'bet') {
            case false:
                return $this->redirectToRoute('ongoing_black_jack');
            default:
                $playerName = $session->get("player_name");
                $numCardHands = $session->get("num_card_hands");
                $currentDeck = $session->get('current_deck');
                $allBets = [
                    $session->get("bet1"),
                    $session->get("bet2"),
                    $session->get("bet3")
                ];

                $gameLogic = new BlackJackLogicTwo();
                /** @var string $playerName
                * @var int $numCardHands
                * @var array<int> $allBets
                *  @var array<mixed> $currentDeck
                */
                $toStore = $gameLogic->newGame($playerName, $numCardHands, $allBets, $currentDeck);
                // $data = [];
                foreach ($toStore as $key => $value) {
                    //$session->set("current_deck", $shuffledDeck);
                    $session->set($key, $value);
                }

                /*
                $data = $toStore;
                $data["player_hand"] = $toStore['player_hand']->getAsArray();
                $data["bank_hand"] = $toStore['bank_hand']->getAsArray();
                // $data = array_combine(array_keys($toStore), $toStore);
                $data = [
                    "player_hand" => "",
                    "bank_hand" => "",
                    "current_player" => 'player',
                    "player_name" => $playerName,
                    "player_hand_score" => 0,
                    "bank_hand_score" => 0,
                    "last_player_card" => "-",
                    "last_bank_card" => "-",
                    "game_state" => 'ongoing'
                ]; */

        }
        // return $this->render('blackjack/play_page.html.twig', $data);
        return $this->redirectToRoute('ongoing_black_jack');
    }

    #[Route("/proj/play/draw_card", name: "draw_one_card_black_jack", methods: ['POST'])]
    public function drawOneCardBlackJackPost(
        SessionInterface $session
    ): Response {
        $currentDeck = $session->get("current_deck");
        $currentPlayer = $session->get('current_player');
        $playerHand = $session->get("player_hand");
        $bankHand = $session->get("bank_hand");
        $playerWins = $session->get("player_wins");
        $bankWins = $session->get("bank_wins");

        if (is_countable($currentDeck) && count($currentDeck) === 0) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by deleting session'
            );
            return $this->redirectToRoute('ongoing_black_jack');
        }

        $gameLogic = new BlackJackLogic();

        $handToUse = "";

        $opponentHand = "";
        switch ($currentPlayer) {
            case 'player':
                $handToUse = $playerHand;
                $opponentHand = $bankHand;
                break;
            case 'bank':
                $handToUse = $bankHand;
                $opponentHand = $playerHand;
                break;
        }

        /**
         * @var CardHand $handToUse
         * @var array<CardGraphic> $currentDeck
         * @var string $currentPlayer
         * @var CardHand $opponentHand
         * @var int $playerWins
         * @var int $bankWins
         *
        */
        $toStore = $gameLogic->drawOneCard(
            $handToUse,
            $currentDeck,
            $currentPlayer,
            $opponentHand,
            $playerWins,
            $bankWins
        );

        foreach ($toStore as $key => $value) {
            $session->set($key, $value);
        }

        return $this->redirectToRoute('ongoing_black_jack');
    }

    #[Route("/proj/play/ongoing", name: "ongoing_black_jack")]
    public function ongoingBlackJackPlay(SessionInterface $session): Response
    {
        $gameState = $session->get("game_state");

        $gameLogic = new BlackJackOngoing();


        $money = $session->get("money");
        $lastBankCard = $session->get("last_bank_card");
        $bankHand = $session->get("bank_hand");
        $bankHandScore = $session->get("bank_hand_score");
        $currentPlayer = $session->get("current_player");
        $playerName = $session->get("player_name");
        /**
         * @var int $money
         * @var string $gameState
         * @var string $lastBankCard
         * @var CardHand $bankHand
         * @var array<int> $bankHandScore
         * @var string $currentPlayer
         * @var string $playerName
         *
        */
        $data = $gameLogic->ongoingDataCreator(
            $gameState,
            $money,
            $lastBankCard,
            $bankHand,
            $bankHandScore,
            $currentPlayer,
            $playerName,
            ['player_spot_one' => $session->get("player_spot_one"),
            'player_spot_two' => $session->get("player_spot_two"),
            'player_spot_three' => $session->get("player_spot_three"),
            'bet1' => $session->get("bet1"),
            'bet2' => $session->get("bet2"),
            'bet3' => $session->get("bet3"),
            'num_card_hands' => $session->get("num_card_hands")]
        );

        /*
        $playerHand = $session->get("player_hand");
        $lastPlayerCard = $session->get("last_player_card");
        $lastBankCard = $session->get("last_bank_card");
        $bankHand = $session->get("bank_hand");
        $playerHandScore = $session->get("player_hand_score");
        $bankHandScore = $session->get("bank_hand_score");
        $currentPlayer = $session->get("current_player");
        $playerName = $session->get("player_name");
        $playerWins = $session->get("player_wins");
        $bankWins = $session->get("bank_wins");

        $playerSpotOne = $session->get("player_spot_one");
        $playerSpotTwo = $session->get("player_spot_two");
        $playerSpotThree = $session->get("player_spot_three");
        */

        // $currentPlayer = $session->get("current_player");

        /*switch ($gameState) {
            case "win":
                $this->addFlash(
                    'notice',
                    $currentPlayer . ' won!'
                );
                break;
            case "loss":
                $this->addFlash(
                    'warning',
                    $currentPlayer . ' lost!'
                );
                break;
        }*/

        return $this->render('blackjack/play_page.html.twig', $data);
    }
}
