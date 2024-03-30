<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportControllerJson
{
    #[Route("/api/quote")]
    public function jsonNumber(): Response
    {
        $quotes = array(
            "Better to remain silent and be thought a fool than to speak out and remove all doubt. -Abraham Lincoln", 
            "I don’t believe in astrology; I’m a Sagittarius and we’re skeptical. -Arthur C.Clarke", 
            "A bank is a place that will lend you money if you can prove that you don’t need it. -Bob Hope", 
            "Age is an issue of mind over matter. If you don’t mind, it doesn’t matter. -Mark Twain", 
            "A day without sunshine is like, you know, night. -Steve Martin", 
            "A bargain is something you don’t need at a price you can’t resist. -Franklin Jones", 
            "I have learned from my mistakes, and I am sure I can repeat them exactly. -Peter Cook"
        );

        $number = random_int(0, sizeof($quotes)-1);
        date_default_timezone_set("Europe/Stockholm");
        $data = [
            'Date created: ' => date("Y-m-d H:i:s"),
            'Quote: ' => $quotes[$number],
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
