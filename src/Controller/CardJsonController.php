<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\DeckOfCards;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardJsonController
{
    #[Route("/api/deck", methods: ['GET'])]
    public function jsonDeck(Request $request, SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $inOrderDeck = $deck->getAllCardsInOrder();
        $session->remove('deck_available');
        $session->set("current_deck", $inOrderDeck);

        $cardsToSend = [];
        foreach ($inOrderDeck as $x) {
            array_push($cardsToSend, $x->getAsString());
        }
        $data = [
            "deckOfCards" => $cardsToSend
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/deck/shuffle", name: "shuffle_json_post", methods: ['POST'])]
    public function jsonShuffle(Request $request, SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $shuffledDeck = $deck->getAllCardsShuffled();
        $session->remove('deck_available');
        $session->set("current_deck", $shuffledDeck);

        $cardsToSend = [];
        foreach ($shuffledDeck as $x) {
            array_push($cardsToSend, $x->getAsString());
        }
        $data = [
            "deckOfCards" => $cardsToSend
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/deck/draw", name: "draw_one_json_post", methods: ['POST'])]
    public function jsonDrawOne(Request $request, SessionInterface $session): Response
    {
        $deckAvailable = $session->get("deck_available");
        if (!$deckAvailable) {
            $deck = new DeckOfCards();
            $shuffledDeck = $deck->getAllCardsShuffled();
        } else {
            $shuffledDeck = $session->get("current_deck");
        }

        $drawnCards = [];
        $howMany = 0;
        if (count($shuffledDeck) === 0) {
            $data = [
                'warning' =>
                'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session'
            ];
        } else {
            $drawnCards = [array_pop($shuffledDeck)];
            $cardsToSend = [];
            foreach ($drawnCards as $x) {
                array_push($cardsToSend, $x->getAsString());
            }

            $data = [
                "cardsLeft" => count($shuffledDeck),
                "cards" => $cardsToSend
            ];

        }

        $session->set("deck_available", "yes");
        $session->set("current_deck", $shuffledDeck);

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/deck/draw/{num<\d+>}", name: "draw_many_num_json_post", methods: ['POST'])]
    public function jsonDrawManyNum(int $num, Request $request, SessionInterface $session): Response
    {
        $deckAvailable = $session->get("deck_available");
        if (!$deckAvailable) {
            $deck = new DeckOfCards();
            $shuffledDeck = $deck->getAllCardsShuffled();
        } else {
            $shuffledDeck = $session->get("current_deck");
        }

        if ($num > count($shuffledDeck)) {
            $data = [
                'warning' => 'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session'
            ];
            $num = 0;
        } else {

            $drawnCards = [];
            for ($x = 0; $x < $num; $x++) {
                array_push($drawnCards, array_pop($shuffledDeck));
            }

            $cardsToSend = [];
            foreach ($drawnCards as $x) {
                array_push($cardsToSend, $x->getAsString());
            }

            $data = [
                "cardsLeft" => count($shuffledDeck),
                "cards" => $cardsToSend
            ];

        }
        $session->set("deck_available", "yes");
        $session->set("current_deck", $shuffledDeck);

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;

    }

    /*
    #[Route("api/deck/draw_many", name: "draw_many_json_post", methods: ['POST'])]
    public function jsonDrawMany(Request $request, SessionInterface $session): Response
    {
        $numCardsToDraw = $request->request->get('numCardsToDraw');
        // $session->set('numCardsToDraw', $numCardsToDraw);

        return $this->redirectToRoute('draw_many_num_json_post', ["num" => $numCardsToDraw]);
        // $this->jsonDrawManyNum($numCardsToDraw, $request, $session );
    }
    */
}
