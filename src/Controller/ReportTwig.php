<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky")]
    public function number(): Response
    {   

        $images = array("img/number1.jpg", "img/number2.jpg", "img/number3.jpg", "img/number4.jpg"

        );
        $number = random_int(1, sizeof($images));

        $data = [
            'number' => $number,
            'rand_image' => $images[$number-1]
        ];

        return $this->render('lucky.html.twig', $data);
    }
    #[Route("/home", name: "home")]
    public function home(): Response
    {
        return $this->render('home.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
    #[Route("/api", name: "api")]
    public function api(): Response
    {
        return $this->render('api.html.twig');
    }
    #[Route("/", name: "index")]
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }
}