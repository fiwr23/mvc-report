<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Black Jack About controller.
 */
class BlackJackAboutController extends AbstractController
{
    #[Route("/proj/about", name: "black_jack_about")]
    public function blackJackAbout(): Response
    {
        return $this->render('blackjack/about_page.html.twig');
    }
}
