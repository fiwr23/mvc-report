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
 * Card game controller.
 */
class CardGameController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "show_deck")]
    public function showDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $inOrderDeck = $deck->getAllCardsInOrder();
        $session->remove('deck_available');
        $session->set("current_deck", $inOrderDeck);
        $data = [
            "deckOfCards" => $inOrderDeck,
            "whatWay" => "In Order"
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "shuffled_deck")]
    public function shuffleDeck(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $shuffledDeck = $deck->getAllCardsShuffled();
        $session->set("current_deck", $shuffledDeck);
        $session->remove('deck_available');
        $data = [
            "deckOfCards" => $shuffledDeck,
            "whatWay" => "Shuffled"
        ];

        return $this->render('card/deck.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "draw_one_card")]
    public function drawOneCard(SessionInterface $session): Response
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
        $howMany = 0;
        if (is_countable($shuffledDeck) && count($shuffledDeck) === 0) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session'
            );
        } elseif (is_array($shuffledDeck)) {
            $drawnCards = [array_pop($shuffledDeck)];
            $howMany = 1;
        }
        $session->set("deck_available", "yes");
        $session->set("current_deck", $shuffledDeck);
        $data = [
            "deckOfCards" => $drawnCards,
            "howMany" => $howMany,
            "cardsLeft" => (is_countable($shuffledDeck)) ? count($shuffledDeck) : ''
        ];


        return $this->render('card/drawn_cards.html.twig', $data);
    }
}
