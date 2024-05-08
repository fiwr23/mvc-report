<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\GameLogic;

// use App\Dice\DiceGraphic;
// use App\Dice\DiceHand;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Card game controller two.
 */
class CardGameControllerTwo extends AbstractController
{
    #[Route("/card/deck/draw/{num<\d+>}", name: "draw_many_cards")]
    public function drawManyCards(int $num, SessionInterface $session): Response
    {
        /*
        $numCardsToDraw = $session->get("numCardsToDraw");
        if ($numCardsToDraw) {
            $num = $numCardsToDraw;
        } */

        $deckAvailable = $session->get("deck_available");
        switch (!$deckAvailable) {
            case true:
                $deck = new DeckOfCards();
                $shuffledDeck = $deck->getAllCardsShuffled();
                break;
            default:
                $shuffledDeck = $session->get("current_deck");
        }

        if (is_countable($shuffledDeck) && $num > count($shuffledDeck)) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session'
            );
            $num = 0;
        }

        $drawnCards = [];
        $data = [];
        if (is_array($shuffledDeck)) {
            for ($x = 0; $x < $num; $x++) {
                array_push($drawnCards, array_pop($shuffledDeck));
            }

            $session->set("deck_available", "yes");
            $session->set("current_deck", $shuffledDeck);
            $data = [
                "deckOfCards" => $drawnCards,
                "howMany" => $num,
                "cardsLeft" => count($shuffledDeck)
            ];
        }


        return $this->render('card/draw_many_cards.html.twig', $data);
    }

    #[Route("/card/deck/draw_post", name: "draw_many_post", methods: ['POST'])]
    public function drawManyPost(
        Request $request
        // SessionInterface $session
    ): Response {
        $numCardsToDraw = $request->request->get('numCardsToDraw');
        // $session->set('numCardsToDraw', $numCardsToDraw);

        return $this->redirectToRoute('draw_many_cards', ["num" => $numCardsToDraw]);
        // $this->drawManyCards($numCardsToDraw);
    }

}
