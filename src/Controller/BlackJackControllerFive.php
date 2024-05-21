<?php

namespace App\Controller;

use App\Card\Card;
use App\Card\CardGraphic;
use App\Card\CardHand;
use App\Card\DeckOfCards;
use App\Card\BlackJackLogic;
use App\Card\BlackJackLogicTwo;
use App\Card\BlackJackOngoing;
use App\Card\BlackJackLogicFour;
use App\Card\BlackJackLogicFive;
use App\Card\BlackJackLogicSix;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Black Jack controller five.
 */
class BlackJackControllerFive extends AbstractController
{
    #[Route("/proj/game/bank_next_move_post", name: "bank_next_move_post", methods: ['POST'])]
    public function bankNextMovePost(
        SessionInterface $session
    ): Response {
        $bankHand = $session->get('bank_hand');
        $playerSpotOne = $session->get('player_spot_one');
        $playerSpotTwo = $session->get('player_spot_two');
        $playerSpotThree = $session->get('player_spot_three');
        $currentDeck = $session->get('current_deck');
        $money = $session->get('money');

        $gameLogic = new BlackJackLogicSix();

        if (is_countable($currentDeck) && count($currentDeck) == 0) {
            $this->addFlash(
                'warning',
                'Too few cards in deck! Reset by deleting session'
            );
            return $this->redirectToRoute('ongoing_black_jack');
        }

        /** @var int $money
         * @var array<mixed> $currentDeck
         * @var CardHand $bankHand
         * @var array<mixed> $playerSpotOne
        *  @var array<mixed>|null $playerSpotTwo
        *  @var array<mixed>|null $playerSpotThree
        */
        $toStore = $gameLogic->banksTurn(
            $money,
            $currentDeck,
            $bankHand,
            $playerSpotOne,
            $playerSpotTwo,
            $playerSpotThree
        );


        foreach ($toStore as $key => $value) {
            $session->set($key, $value);
        }

        return $this->redirectToRoute('ongoing_black_jack');
    }
}
