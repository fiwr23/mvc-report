<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogic;
use App\Card\BlackJackLogicTwo;
use App\Card\BlackJackOngoing;
use App\Card\BlackJackLogicThree;
use App\Card\BlackJackLogicFour;
use App\Card\BlackJackLogicFive;
use App\Card\BlackJackLogicSix;
use App\Card\BlackJackLogicSeven;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Black Jack controller four.
 */
class BlackJackControllerFour extends AbstractController
{
    #[Route("/proj/game/hit_post", name: "hit_black_jack_post", methods: ['POST'])]
    public function hitPost(
        Request $request,
        SessionInterface $session
    ): Response {
        $activeSpot = $request->request->get('active_spot');
        /** @var string $activeSpot
         *
         */
        $playerSpot = $session->get($activeSpot);
        $currentDeck = $session->get('current_deck');
        $playerSpotOne = $session->get('player_spot_one');
        $playerSpotTwo = $session->get('player_spot_two');
        $playerSpotThree = $session->get('player_spot_three');

        $money = $session->get('money');
        $gameLogic = new BlackJackLogicFive();

        if (is_countable($currentDeck) && count($currentDeck) == 0) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by deleting session'
            );
            return $this->redirectToRoute('ongoing_black_jack');
        }

        /**
        * @var array<mixed> $playerSpot
        * @var string $activeSpot
        * @var array<mixed> $currentDeck
        * @var array<mixed> $playerSpotOne
        * @var array<mixed>|null $playerSpotTwo
        * @var array<mixed>|null $playerSpotThree
        * @var int $money
        */
        $toStore = $gameLogic->hit(
            $money,
            $currentDeck,
            $playerSpot,
            $activeSpot,
            $playerSpotOne,
            $playerSpotTwo,
            $playerSpotThree
        );


        foreach ($toStore as $key => $value) {
            $session->set($key, $value);
        }

        // control if all cards either won or lost
        $playerSpotOne = $session->get('player_spot_one');
        $playerSpotTwo = $session->get('player_spot_two');
        $playerSpotThree = $session->get('player_spot_three');

        $gameLogic = new BlackJackLogicFive();
        /**
        * @var array<mixed> $playerSpotOne
        * @var array<mixed>|null $playerSpotTwo
        * @var array<mixed>|null $playerSpotThree
        * @var int $money
        */
        $areAllSpotsWonOrLost = $gameLogic->allSpotsWonOrLost(
            $playerSpotOne,
            $playerSpotTwo,
            $playerSpotThree
        );

        if ($areAllSpotsWonOrLost === 'yes') {
            $session->set('game_state', 'finished');
        }

        $gameLogic = new BlackJackLogicSix();
        /**
        * @var array<mixed> $playerSpotOne
        * @var array<mixed>|null $playerSpotTwo
        * @var array<mixed>|null $playerSpotThree
        * @var int $money
        */
        $isItBanksTurn = $gameLogic->isItBanksTurn($playerSpotOne, $playerSpotTwo, $playerSpotThree);

        // if banks turn sum banks cards including hidden one
        if ($isItBanksTurn === 'yes') {
            $money = $session->get('money');
            $bankHand = $session->get('bank_hand');
            $gameLogic = new BlackJackLogicThree();
            /**
            * @var CardHand $bankHand
            */
            $bankHandScore = $gameLogic->sumCurrentHand($bankHand);
            $session->set('current_player', 'bank');
            $session->set('bank_hand_score', $bankHandScore);

            $lastBankCard = $bankHand->getAsArray();
            /**
            * @var array<CardGraphic> $lastBankCard
            */
            $lBCEnd = end($lastBankCard);
            /**
            * @var CardGraphic $lBCEnd
            */
            $lBCAsString = $lBCEnd->getTextAsString();
            $session->set('last_bank_card', $lBCAsString);

            $gameLogic = new BlackJackLogicSeven();
            /**
            * @var cardHand $bankHand
            * @var array<mixed> $playerSpotOne
            * @var array<mixed>|null $playerSpotTwo
            * @var array<mixed>|null $playerSpotThree
            * @var int $money
            */
            $toStore = $gameLogic->checkBankAndSpotScores(
                $money,
                $bankHand,
                $playerSpotOne,
                $playerSpotTwo,
                $playerSpotThree
            );

            foreach ($toStore as $key => $value) {
                $session->set($key, $value);
            }
        }
        return $this->redirectToRoute('ongoing_black_jack');
    }

    #[Route("/proj/game/stay_post", name: "stay_black_jack_post", methods: ['POST'])]
    public function stayPost(
        Request $request,
        SessionInterface $session
    ): Response {
        $activeSpot = $request->request->get('active_spot');
        /** @var string $activeSpot
         *
         */
        $playerSpot = $session->get($activeSpot);
        /**
        * @var array<mixed> $playerSpot
        */
        $playerSpot['spot_state'] = 'stay';
        /** @var string $activeSpot
         * @var array<mixed> $playerSpot
         */
        $session->set($activeSpot, $playerSpot);

        $playerSpotOne = $session->get('player_spot_one');
        $playerSpotTwo = $session->get('player_spot_two');
        $playerSpotThree = $session->get('player_spot_three');


        $gameLogic = new BlackJackLogicSix();
        /**
        * @var array<mixed> $playerSpotOne
        *  @var array<mixed>|null $playerSpotTwo
        *  @var array<mixed>|null $playerSpotThree
        */
        $isItBanksTurn = $gameLogic->isItBanksTurn($playerSpotOne, $playerSpotTwo, $playerSpotThree);

        // if banks turn sum banks cards including hidden one
        if ($isItBanksTurn === 'yes') {
            $money = $session->get('money');
            $bankHand = $session->get('bank_hand');
            $gameLogic = new BlackJackLogicThree();
            /**
            * @var cardHand $bankHand
            */
            $bankHandScore = $gameLogic->sumCurrentHand($bankHand);
            $session->set('current_player', 'bank');
            $session->set('bank_hand_score', $bankHandScore);
            $lastBankCard = $bankHand->getAsArray();
            /**
            * @var array<CardGraphic> $lastBankCard
            */
            $lBCEnd = end($lastBankCard);
            /**
            * @var CardGraphic $lBCEnd
            */
            $lBCAsString = $lBCEnd->getTextAsString();
            $session->set('last_bank_card', $lBCAsString);

            $gameLogic = new BlackJackLogicSeven();
            /** @var int $money
             * @var CardHand $bankHand
             * @var array<mixed> $playerSpotOne
            *  @var array<mixed>|null $playerSpotTwo
            *  @var array<mixed>|null $playerSpotThree
            */
            $toStore = $gameLogic->checkBankAndSpotScores(
                $money,
                $bankHand,
                $playerSpotOne,
                $playerSpotTwo,
                $playerSpotThree
            );

            foreach ($toStore as $key => $value) {
                $session->set($key, $value);
            }
        }
        return $this->redirectToRoute('ongoing_black_jack');
    }

}
