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

/**
 * Card Json controller.
 */
class CardJsonController
{
    #[Route("/api/deck", methods: ['GET'])]
    public function jsonDeck(SessionInterface $session): Response
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
    public function jsonShuffle(SessionInterface $session): Response
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
    public function jsonDrawOne(SessionInterface $session): Response
    {
        $deckAvailable = $session->get("deck_available");
        switch (!$deckAvailable) {
            case true:
                $deck = new DeckOfCards();
                $shuffledDeck = $deck->getAllCardsShuffled();
                break;
            default:
                $shuffledDeck = $session->get("current_deck");
        }

        $drawnCards = [];
        $data = [];
        if (is_countable($shuffledDeck)) {
            // $howMany = 0;
            if (count($shuffledDeck) === 0) {
                $data = [
                    'warning' =>
                    'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session'
                ];
            } elseif (is_array($shuffledDeck)) {
                $drawnCards = [array_pop($shuffledDeck)];
                $cardsToSend = [];

                /** @var CardGraphic $x*/
                foreach ($drawnCards as $x) {
                    array_push($cardsToSend, $x->getAsString());
                }

                $data = [
                    "cardsLeft" => count($shuffledDeck),
                    "cards" => $cardsToSend
                ];

            }
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
    public function jsonDrawManyNum(int $num, SessionInterface $session): Response
    {
        $deckAvailable = $session->get("deck_available");
        switch (!$deckAvailable) {
            case true:
                $deck = new DeckOfCards();
                $shuffledDeck = $deck->getAllCardsShuffled();
                break;
            default:
                $shuffledDeck = $session->get("current_deck");
        }

        $data = [];

        if (is_array($shuffledDeck) && is_countable($shuffledDeck)) {
            switch ($num > count($shuffledDeck)) {
                case true:
                    $data = [
                        'warning' => 'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session'
                    ];
                    $num = 0;
                    break;
                default:

                    $drawnCards = [];
                    for ($x = 0; $x < $num; $x++) {
                        array_push($drawnCards, array_pop($shuffledDeck));
                    }

                    $cardsToSend = [];
                    if (is_array($drawnCards)) {
                        /** @var CardGraphic $x*/
                        foreach ($drawnCards as $x) {
                            array_push($cardsToSend, $x->getAsString());
                        }
                    }
                    $data = [
                        "cardsLeft" => count($shuffledDeck),
                        "cards" => $cardsToSend
                    ];

            }
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
