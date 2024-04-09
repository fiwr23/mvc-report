<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    protected $spadesGraphic = [
        "🂡", "🂢", "🂣", "🂤", "🂥", "🂦", "🂧", "🂨", "🂩", "🂪", "🂫", "🂬", "🂭", "🂮"
    ];
    protected $heartsGraphic = [
        "🂱", "🂲", "🂳", "🂴", "🂵", "🂶", "🂷", "🂸", "🂹", "🂺", "🂻", "🂼", "🂽", "🂾"
    ];
    protected $diamondsGraphic = [
        "🃁", "🃂", "🃃", "🃄", "🃅", "🃆", "🃇", "🃈", "🃉", "🃊", "🃋", "🃌", "🃍", "🃎"
    ];
    protected $clubsGraphic = [
        "🃑", "🃒", "🃓", "🃔", "🃕", "🃖", "🃗", "🃘", "🃙", "🃚", "🃛", "🃜", "🃝", "🃞"
    ];

    private $spadesText = [
        "Ace of Spades", "Two of Spades", "Three of Spades", "Four of Spades",
        "Five of Spades", "Six of Spades", "Seven of Spades", "Eight of Spades",
        "Nine of Spades", "Ten of Spades", "Jack of Spades", "Knight of Spades",
        "Queen of Spades", "King of Spades"
    ];

    private $heartsText = [
        "Ace of Hearts", "Two of Hearts", "Three of Hearts", "Four of Hearts",
        "Five of Hearts", "Six of Hearts", "Seven of Hearts", "Eight of Hearts",
        "Nine of Hearts", "Ten of Hearts", "Jack of Hearts", "Knight of Hearts",
        "Queen of Hearts", "King of Hearts"
    ];

    private $diamondsText = [
        "Ace of Diamonds", "Two of Diamonds", "Three of Diamonds", "Four of Diamonds",
        "Five of Diamonds", "Six of Diamonds", "Seven of Diamonds", "Eight of Diamonds",
        "Nine of Diamonds", "Ten of Diamonds", "Jack of Diamonds", "Knight of Diamonds",
        "Queen of Diamonds", "King of Diamonds"
    ];

    private $clubsText = [
        "Ace of Clubs", "Two of Clubs", "Three of Clubs", "Four of Clubs",
        "Five of Clubs", "Six of Clubs", "Seven of Clubs", "Eight of Clubs",
        "Nine of Clubs", "Ten of Clubs", "Jack of Clubs", "Knight of Clubs",
        "Queen of Clubs", "King of Clubs"
    ];

    public function combineCards($a, $b): array
    {
        return [$a,$b];
    }
    public function getAllCardsInOrder(): array
    {
        $spades = array_map("self::combineCards", $this->spadesGraphic, $this->spadesText);
        $hearts = array_map("self::combineCards", $this->heartsGraphic, $this->heartsText);
        $diamonds = array_map("self::combineCards", $this->diamondsGraphic, $this->diamondsText);
        $clubs = array_map("self::combineCards", $this->clubsGraphic, $this->clubsText);

        $allCards = array_merge($spades, $hearts, $diamonds, $clubs);
        $diceArray = [];
        foreach ($allCards as $x) {

            array_push($diceArray, new CardGraphic($x[1], $x[0]));
        }
        // return $allCards;
        return $diceArray;
    }

    public function getAllCardsShuffled(): array
    {
        /*
        $spades = array_map("self::combineCards", $this->spadesGraphic, $this->spadesText);
        $hearts = array_map("self::combineCards", $this->heartsGraphic, $this->heartsText);
        $diamonds = array_map("self::combineCards", $this->diamondsGraphic, $this->diamondsText);
        $clubs = array_map("self::combineCards", $this->clubsGraphic, $this->clubsText);

        $allCards = array_merge($spades, $hearts, $diamonds, $clubs);
        */
        $allCards = self::getAllCardsInOrder();

        shuffle($allCards);
        return $allCards;
    }
}
