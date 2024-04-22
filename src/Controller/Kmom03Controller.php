<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\GameLogic;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Kmom03Controller extends AbstractController
{
    #[Route("/game", name: "card_game_home")]
    public function cardGameHome(): Response
    {
        return $this->render('card/card_game_home.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function gameDoc(): Response
    {
        return $this->render('card/game_doc.html.twig');
    }

    #[Route("/game/play", name: "play_game")]
    public function cardGamePlay(SessionInterface $session): Response
    {
        $gameState = $session->get("game_state");
        switch (!$gameState) {
            case false:
                return $this->redirectToRoute('ongoing');
            default:
                $gameLogic = new GameLogic();
                $toStore = $gameLogic->newGame();
                foreach ($toStore as $key => $value) {
                    //$session->set("current_deck", $shuffledDeck);
                    $session->set($key, $value);
                    //$session->remove('deck_available');
                }

                $data = [
                    "player_hand" => "",
                    "bank_hand" => "",
                    "current_player" => 'player',
                    "player_hand_score" => 0,
                    "bank_hand_score" => 0,
                    "last_player_card" => "-",
                    "last_bank_card" => "-",
                    "player_wins" => 0,
                    "bank_wins" => 0,
                    "game_state" => 'ongoing'
                ];

        }

        return $this->render('card/play_page.html.twig', $data);
    }

    #[Route("/game/play/draw_card", name: "draw_one_card_21", methods: ['POST'])]
    public function drawOneCard21Post(
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
            return $this->redirectToRoute('ongoing');
        }

        $gameLogic = new GameLogic();

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

        return $this->redirectToRoute('ongoing');
    }

    #[Route("/game/ongoing", name: "ongoing")]
    public function ongoingGamePlay(SessionInterface $session): Response
    {
        $playerHand = $session->get("player_hand");
        $lastPlayerCard = $session->get("last_player_card");
        $lastBankCard = $session->get("last_bank_card");
        $bankHand = $session->get("bank_hand");
        $playerHandScore = $session->get("player_hand_score");
        $bankHandScore = $session->get("bank_hand_score");
        $gameState = $session->get("game_state");
        $currentPlayer = $session->get("current_player");
        $playerWins = $session->get("player_wins");
        $bankWins = $session->get("bank_wins");

        switch ($gameState) {
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
        }

        $gameLogic = new GameLogic();

        /**
         * @var array<int> $playerHandScore
         * @var array<int> $bankHandScore
         *
        */
        $plrHandScoreStr = $gameLogic->scoreString($playerHandScore);
        $bankHandScoreStr = $gameLogic->scoreString($bankHandScore);

        /**
         * @var CardHand $playerHand
         * @var CardHand $bankHand
         *
        */
        $data = [
            "player_hand" => $playerHand->getAsArray(),
            "bank_hand" => $bankHand->getAsArray(),
            "last_player_card" => $lastPlayerCard,
            "last_bank_card" => $lastBankCard,
            "player_hand_score" => $plrHandScoreStr,
            "bank_hand_score" => $bankHandScoreStr,
            "current_player" => $currentPlayer,
            "player_wins" => $playerWins,
            "bank_wins" => $bankWins,
            "game_state" => $gameState
        ];

        return $this->render('card/play_page.html.twig', $data);
    }

    #[Route("/game/play/switch_to_bank", name: "switch_to_bank_21", methods: ['POST'])]
    public function switchToBankPost(
        SessionInterface $session
    ): Response {
        $session->set("current_player", 'bank');
        $playerHand = $session->get("player_hand");
        $bankHand = $session->get("bank_hand");
        $currentDeck = $session->get("current_deck");
        $playerWins = $session->get("player_wins");
        $bankWins = $session->get("bank_wins");

        if (is_countable($currentDeck) && count($currentDeck) === 0) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by deleting session'
            );
            return $this->redirectToRoute('ongoing');
        }
        $gameLogic = new GameLogic();

        /**
         * @var CardHand $bankHand
         * @var array<CardGraphic> $currentDeck
         * @var CardHand $playerHand
         * @var int $playerWins
         * @var int $bankWins
         *
        */
        $toStore = $gameLogic->drawOneCard(
            $bankHand,
            $currentDeck,
            'bank',
            $playerHand,
            $playerWins,
            $bankWins
        );

        foreach ($toStore as $key => $value) {
            $session->set($key, $value);
        }

        return $this->redirectToRoute('ongoing');
    }

    #[Route("/game/play/next_round_21", name: "next_round_21", methods: ['POST'])]
    public function nextRound21Post(
        SessionInterface $session
    ): Response {

        $gameLogic = new GameLogic();

        $toStore = $gameLogic->nextRound();

        foreach ($toStore as $key => $value) {
            $session->set($key, $value);
        }

        return $this->redirectToRoute('ongoing');
    }

    #[Route("/api/game", methods: ['GET'])]
    public function apiGame(SessionInterface $session): Response
    {
        $playerHand = $session->get("player_hand");
        $bankHand = $session->get("bank_hand");
        $currentPlayer = $session->get("current_player");
        $currentDeck = $session->get("current_deck");
        $playerWins = $session->get("player_wins");
        $bankWins = $session->get("bank_wins");
        $gameState = $session->get("game_state");

        switch (in_array($gameState, ['ongoing', 'win', 'loss'])) {
            case false:
                $this->addFlash(
                    'warning',
                    'No game is in progress. Start a game before trying again!'
                );
                return $this->redirectToRoute('api');
        }

        $deckToSend = [];
        /**
         * @var array<CardGraphic> $currentDeck
         */
        foreach ($currentDeck as $x) {
            array_push($deckToSend, $x->getAsString());
        }

        /**
         * @var CardHand $playerHand
         */
        $plrHandArr = $playerHand->getAsArray();
        $plrHandToSend = [];

        foreach ($plrHandArr as $x) {
            array_push($plrHandToSend, $x->getAsString());
        }

        /**
         * @var CardHand $bankHand
         */
        $bankHandArr = $bankHand->getAsArray();
        $bankHandToSend = [];

        foreach ($bankHandArr as $x) {
            array_push($plrHandToSend, $x->getAsString());
        }
        $data = [
            "player_hand" => $plrHandToSend,
            "bank_hand" => $bankHandToSend,
            "player_wins" => $playerWins,
            "bank_wins" => $bankWins,
            "game_state" => $gameState,
            "current_player" => $currentPlayer,
            "current_deck" => $deckToSend

        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
