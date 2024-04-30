<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller for session page.
 */
class SessionController extends AbstractController
{
    #[Route("/session", name: "view_session", methods: ['GET'])]
    public function viewSession(
        SessionInterface $session
    ): Response {
        $wholeSession = $session->all();


        $data = [
            "wholeSession" => $wholeSession
        ];

        return $this->render('session.html.twig', $data);
    }

    #[Route("/session/delete", name: "clear_session", methods: ['GET'])]
    public function clearSession(
        SessionInterface $session
    ): Response {
        $session->clear();
        $this->addFlash(
            'notice',
            'Session has been deleted!'
        );
        return $this->redirectToRoute('view_session');
    }

}
