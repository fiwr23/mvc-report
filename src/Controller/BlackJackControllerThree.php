<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogic;
use App\Card\BlackJackLogicTwo;
use App\Card\BlackJackLogicThree;
use App\Card\BlackJackOngoing;
use App\Card\BlackJackLogicFour;
use App\Card\BlackJackLogicSix;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Black Jack controller two.
 */
class BlackJackControllerThree extends AbstractController
{
    #[Route("/proj/game/bet", name: "bet_black_jack")]
    public function blackJackBet(SessionInterface $session): Response
    {
        $numCardHands = $session->get('num_card_hands');
        $playerName = $session->get('player_name');
        $money = $session->get('money');

        if ($money < $numCardHands) {
            $this->addFlash(
                'warning',
                'To little money left! Delete session to play again'
            );
            return $this->redirectToRoute('play_game_black_jack');
        }

        $data = [
            'num_card_hands' => $numCardHands,
            'player_name' => $playerName,
            'money' => $money,
            'game_state' => 'ongoing',
            'current_player' => 'player'
        ];
        return $this->render('blackjack/bet_page.html.twig', $data);
    }

    #[Route("/proj/game/bet_post", name: "bet_black_jack_post", methods: ['POST'])]
    public function addChoicesPost(
        Request $request,
        SessionInterface $session
    ): Response {
        $money = $session->get('money');
        $bet1 = (int)$request->request->get('bet1');
        $bet2 = (int)$request->request->get('bet2');
        $bet3 = (int)$request->request->get('bet3');

        // $playerSpotOne = $session->get('player_spot_one');
        // $playerSpotOne['spot_player_bet'] = $bet1;
        // $playerSpotOne['TEST'] = $bet1;
        // $session->set('player_spot_one', $playerSpotOne);
        $money = $money - $bet1;
        if ($bet2) {
            $money = $money - $bet2;
        }

        if ($bet3) {
            $money = $money - $bet3;
        }

        $session->set('bet1', $bet1);
        $session->set('bet2', $bet2);
        $session->set('bet3', $bet3);
        $session->set('money', $money);
        $session->set('game_state', 'bet');
        return $this->redirectToRoute('play_game_black_jack');
    }

    #[Route("/proj/game/second_round_of_cards_post", name: "second_round_of_cards_black_jack_post", methods: ['POST'])]
    public function secondRoundOfCardsPost(
        SessionInterface $session
    ): Response {

        $playerSpotOne = $session->get("player_spot_one");
        $bankHand = $session->get("bank_hand");
        $numCardHands = $session->get("num_card_hands");
        $currentDeck = $session->get('current_deck');
        $money = $session->get('money');
        $gameLogic = new BlackJackLogicFour();

        if (is_countable($currentDeck) && count($currentDeck) < ($numCardHands + 1)) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by deleting session'
            );
            return $this->redirectToRoute('ongoing_black_jack');
        }

        $toStore = [];
        if ($numCardHands == 2) {
            $playerSpotTwo = $session->get("player_spot_two");

            /** @var CardHand $bankHand
             * @var int $money
            * @var array<CardGraphic> $currentDeck
            * @var array<mixed> $playerSpotOne
            *  @var array<mixed>|null $playerSpotTwo
            */
            $toStore = $gameLogic->secondRoundOfCards($bankHand, $money, $currentDeck, $playerSpotOne, $playerSpotTwo);
        } elseif ($numCardHands == 3) {
            $playerSpotTwo = $session->get("player_spot_two");
            $playerSpotThree = $session->get("player_spot_three");
            /**
             * @var CardHand $bankHand
             * @var int $money
            * @var array<CardGraphic> $currentDeck
            * @var array<mixed> $playerSpotOne
            * @var array<mixed>|null $playerSpotTwo
            * @var array<mixed>|null $playerSpotThree
            */
            $toStore = $gameLogic->secondRoundOfCards($bankHand, $money, $currentDeck, $playerSpotOne, $playerSpotTwo, $playerSpotThree);
        } elseif ($numCardHands == 1) {
            /**
             * @var CardHand $bankHand
             * @var int $money
            * @var array<CardGraphic> $currentDeck
            * @var array<mixed> $playerSpotOne
            */
            $toStore = $gameLogic->secondRoundOfCards($bankHand, $money, $currentDeck, $playerSpotOne);
        }

        foreach ($toStore as $key => $value) {
            $session->set($key, $value);
        }

        $playerSpotOne = $session->get('player_spot_one');
        $playerSpotTwo = $session->get('player_spot_two');
        $playerSpotThree = $session->get('player_spot_three');

        $gameLogic = new BlackJackLogicSix();
        /**
        * @var array<mixed> $playerSpotOne
        * @var array<mixed>|null $playerSpotTwo
        * @var array<mixed>|null $playerSpotThree
        */
        $isItBanksTurn = $gameLogic->isItBanksTurn($playerSpotOne, $playerSpotTwo, $playerSpotThree);

        if ($isItBanksTurn === 'yes') {
            $bankHand = $session->get('bank_hand');
            $gameLogic = new BlackJackLogicThree();
            /**
             * @var CardHand $bankHand
             */
            $bankHandScore = $gameLogic->sumCurrentHand($bankHand);
            $session->set('bank_hand_score', $bankHandScore);
            $session->set('current_player', 'bank');
        }

        // $session->set('is_it_bank_turn', $isItBanksTurn);

        return $this->redirectToRoute('ongoing_black_jack');
    }
}
