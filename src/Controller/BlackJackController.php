<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogic;
use App\Card\BlackJackLogicTwo;
use App\Card\BlackJackLogicThree;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Black Jack controller.
 */
class BlackJackController extends AbstractController
{
    #[Route("/proj", name: "black_jack_home")]
    public function blackJackHome(SessionInterface $session): Response
    {
        // $gameLogic = new BlackJackLogicThree();
        //  $playerName = $gameLogic->testFunc($session);

        $playerName = $session->get('player_name');
        if (!$playerName) {
            $playerName = "";
        }
        $data = [
            "player_name" => $playerName];

        return $this->render('blackjack/home.html.twig', $data);
    }

    #[Route("/proj/add_choices_post", name: "add_choices_black_jack", methods: ['POST'])]
    public function addChoicesPost(
        Request $request,
        SessionInterface $session
    ): Response {
        $numCardHands = $request->request->get('numCardHands');
        $playerName = $request->request->get('playerName');
        $session->set('num_card_hands', $numCardHands);
        $session->set('player_name', $playerName);
        $money = $session->get('money');

        if (!$money) {
            $session->set('money', 250);
        }

        return $this->redirectToRoute('bet_black_jack');
    }

    #[Route("/proj/play/switch_to_bank", name: "switch_to_bank_black_jack", methods: ['POST'])]
    public function switchToBankBlackJackPost(
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
            return $this->redirectToRoute('ongoing_black_jack');
        }
        $gameLogic = new BlackJackLogic();

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

        return $this->redirectToRoute('ongoing_black_jack');
    }

    #[Route("/proj/play/next_round_black_jack", name: "next_round_black_jack", methods: ['POST'])]
    public function nextRoundBlackJackPost(
        SessionInterface $session
    ): Response {

        $gameLogic = new BlackJackLogicTwo();

        $toStore = $gameLogic->nextRound();

        foreach ($toStore as $key => $value) {
            $session->set($key, $value);
        }

        return $this->redirectToRoute("bet_black_jack");
    }
}
