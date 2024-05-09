<?php

namespace App\Card;

use App\Card\CardGraphic;

/**
 * Check cards in deck for drawNumJson, returns data array.
 */
class CheckNumCards
{
    /**
     * Returns data array.
     * @param array<null|CardGraphic> $shuffledDeck
     * @return array<string, array<CardGraphic|string|null>|int|string>
    */
    public function check(int $num, array $shuffledDeck): array
    {
        switch ($num > count($shuffledDeck)) {
            case true:
                return [
                    'warning' => 'Too few cards in deck! Reset by clicking on card/deck/shuffle or delete session',
                    "shuffledDeck" => $shuffledDeck
                ];
            default:

                $drawnCards = [];
                for ($x = 0; $x < $num; $x++) {
                    array_push($drawnCards, array_pop($shuffledDeck));
                }

                $cardsToSend = [];
                if (is_array($drawnCards)) {
                    /** @var CardGraphic $x*/
                    foreach ($drawnCards as $x) {
                        array_push($cardsToSend, $x->getAsString());
                    }
                }
                /** @var int $tmpCount*/
                $tmpCount = count($shuffledDeck);

                /** @var array<string> $cardsToSend*/
                return [
                    "cardsLeft" => $tmpCount,
                    "cards" => $cardsToSend,
                    "shuffledDeck" => $shuffledDeck
                ];
        }
    }
}
