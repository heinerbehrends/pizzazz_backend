<?php

namespace App\ScrabbleLogic;


class MakeRandomLetters
{
    // combines methods and data to generate the seven random letters.
    // $this->makeRandomLetters returns 3 random vowels and 4 random consonants in random order

    public static $vowelsDistribution = [
      'e'=>12, 'a'=>9, 'i'=>9, 'o'=>8,'u'=>4
    ];
    public static $consonantsDistribution = [
      'n'=>6, 'r'=>6, 't'=>6, 'l'=>4, 's'=>4, 'd'=>4, 'g'=>3, 'b'=>2,
      'c'=>2, 'm'=>2, 'p'=>2, 'f'=>2, 'h'=>2, 'v'=>2, 'w'=>2, 'y'=>2,
      'k'=>1, 'j'=>1, 'x'=>1, 'q'=>1, 'z'=>4, '8'=>2
    ];

    public static function makeBagOfLetters($letterDistribution)
    {
      $bagOfLetters = [];
      foreach ($letterDistribution as $letter => $numberOf) {
        for ($i=0; $i<$numberOf; $i++) {
          array_push($bagOfLetters, $letter);
        }
      }
      return $bagOfLetters;
    }

    public static function drawFromBag($numberOf, $bagOfLetters)
    {
      $randomLetters = '';
      for ($i=0; $i<$numberOf; $i++) {
        $randomLetters .= $bagOfLetters[rand(0, count($bagOfLetters) -1)];
      }
      return $randomLetters;
    }

    public static function makeRandomLetters()
    {
      $randomLetters = self::drawFromBag(3, self::makeBagOfLetters(self::$vowelsDistribution))
                     . self::drawFromBag(4, self::makeBagOfLetters(self::$consonantsDistribution));

      return strtoupper(str_shuffle($randomLetters));
    }
}
