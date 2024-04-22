<?php

namespace App\Card;

use App\Card\CardGraphic;

class DeckOfCards
{
    /**
    * @var array<string|int>
    */
    protected $spadesGraphic = [
        "🂡", "🂢", "🂣", "🂤", "🂥", "🂦", "🂧", "🂨", "🂩", "🂪", "🂬", "🂭", "🂮"
    ];

    /**
    * @var array<string|int>
    */
    protected $heartsGraphic = [
        "🂱", "🂲", "🂳", "🂴", "🂵", "🂶", "🂷", "🂸", "🂹", "🂺", "🂼", "🂽", "🂾"
    ];

    /**
    * @var array<string|int>
    */
    protected $diamondsGraphic = [
        "🃁", "🃂", "🃃", "🃄", "🃅", "🃆", "🃇", "🃈", "🃉", "🃊", "🃌", "🃍", "🃎"
    ];

    /**
    * @var array<string|int>
    */
    protected $clubsGraphic = [
        "🃑", "🃒", "🃓", "🃔", "🃕", "🃖", "🃗", "🃘", "🃙", "🃚", "🃜", "🃝", "🃞"
    ];

    /**
    * @var array<string|int>
    */
    private $spadesText = [
        "Ace of Spades", "Two of Spades", "Three of Spades", "Four of Spades",
        "Five of Spades", "Six of Spades", "Seven of Spades", "Eight of Spades",
        "Nine of Spades", "Ten of Spades", "Knight of Spades",
        "Queen of Spades", "King of Spades"
    ];

    /**
    * @var array<string|int>
    */
    private $heartsText = [
        "Ace of Hearts", "Two of Hearts", "Three of Hearts", "Four of Hearts",
        "Five of Hearts", "Six of Hearts", "Seven of Hearts", "Eight of Hearts",
        "Nine of Hearts", "Ten of Hearts", "Knight of Hearts",
        "Queen of Hearts", "King of Hearts"
    ];

    /**
    * @var array<string|int>
    */
    private $diamondsText = [
        "Ace of Diamonds", "Two of Diamonds", "Three of Diamonds", "Four of Diamonds",
        "Five of Diamonds", "Six of Diamonds", "Seven of Diamonds", "Eight of Diamonds",
        "Nine of Diamonds", "Ten of Diamonds", "Knight of Diamonds",
        "Queen of Diamonds", "King of Diamonds"
    ];

    /**
    * @var array<string|int>
    */
    private $clubsText = [
        "Ace of Clubs", "Two of Clubs", "Three of Clubs", "Four of Clubs",
        "Five of Clubs", "Six of Clubs", "Seven of Clubs", "Eight of Clubs",
        "Nine of Clubs", "Ten of Clubs", "Knight of Clubs",
        "Queen of Clubs", "King of Clubs"
    ];

    /**
    * @param  array<string|int> $arrOne
    * @param  array<string|int> $arrTwo
    * @return array<int, array<int|string>>
    * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
    */
    private static function combineCards($arrOne, $arrTwo): array
    {
        return [$arrOne,$arrTwo];
    }
    /**
    * @return array<CardGraphic>
    */
    public function getAllCardsInOrder(): array
    {
        $spades = array_map([__CLASS__,'combineCards'], $this->spadesGraphic, $this->spadesText);
        $hearts = array_map([__CLASS__,'combineCards'], $this->heartsGraphic, $this->heartsText);
        $diamonds = array_map([__CLASS__,'combineCards'], $this->diamondsGraphic, $this->diamondsText);
        $clubs = array_map([__CLASS__,'combineCards'], $this->clubsGraphic, $this->clubsText);

        // $spades = array_map("self::combineCards", $this->spadesGraphic, $this->spadesText);
        // $hearts = array_map("self::combineCards", $this->heartsGraphic, $this->heartsText);
        // $diamonds = array_map("self::combineCards", $this->diamondsGraphic, $this->diamondsText);
        // $clubs = array_map("self::combineCards", $this->clubsGraphic, $this->clubsText);

        $allCards = array_merge($spades, $hearts, $diamonds, $clubs);
        $cardArray = [];
        foreach ($allCards as $x) {
            $str1 = $x[1];
            $str2 = $x[0];
            /**
             * @var string $str1
             * @var string $str2
             */
            array_push($cardArray, new CardGraphic($str1, $str2));
        }
        // return $allCards;

        return $cardArray;
    }
    /**
    * @return array<CardGraphic>
    */
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
