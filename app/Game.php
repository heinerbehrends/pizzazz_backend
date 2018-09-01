<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
      'player1', 'player2', 'player1Solution', 'player2Solution',
      'player1Score', 'player2Score'];

    public function makeBagOfLetters($letterDistribution) {
      $bagOfLetters = [];
      foreach ($letterDistribution as $letter => $numberOf) {
        for ($i=0; $i<$numberOf; $i++) {
          array_push($bagOfLetters, $letter);
        }
      }
      return $bagOfLetters;
    }
    public function drawRandomLetters($numberOf, $bagOfLetters) {
      $randomLetters = '';
      for ($i=0; $i<$numberOf; $i++) {
        $randomLetters .= $bagOfLetters[rand(0, count($bagOfLetters) -1)];
      }
      return $randomLetters;
    }

    public function makeRandomLetters() {
      
      $vowelsDistribution = [
        'e'=>12, 'a'=>9, 'i'=>9, 'o'=>8,'u'=>4
      ];
      $consonantsDistribution = [
        'n'=>6, 'r'=>6, 't'=>6, 'l'=>4, 's'=>4, 'd'=>4, 'g'=>3, 'b'=>2,
        'c'=>2, 'm'=>2, 'p'=>2, 'f'=>2, 'h'=>2, 'v'=>2, 'w'=>2, 'y'=>2,
        'k'=>1, 'j'=>1, 'x'=>1, 'q'=>1, 'z'=>4, '8'=>2
      ];

      $bagOfVowels = $this->makeBagOfLetters($vowelsDistribution);
      $bagOfConsonants = $this->makeBagOfLetters($consonantsDistribution);

      $randomLetters = $this->drawRandomLetters(3, $bagOfVowels);
      $randomLetters .= $this->drawRandomLetters(4, $bagOfConsonants);
      $randomLetters = str_shuffle($randomLetters);

      return strtoupper($randomLetters);
    }
}
