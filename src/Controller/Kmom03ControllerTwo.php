<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\GameLogic;
use App\Card\GameLogicTwo;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Card game controller two for kmom03.
 */
class Kmom03ControllerTwo extends AbstractController
{
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

        $gameLogic = new GameLogicTwo();

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
            array_push($bankHandToSend, $x->getAsString());
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
