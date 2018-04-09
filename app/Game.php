<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
      'player1', 'player2', 'player1Solution', 'player2Solution',
      'player1Score', 'player2Score'];

    public function makeRandomLetters() {
      $vowelsDistribution = [
        'e'=>12, 'a'=>9, 'i'=>9, 'o'=>8,'u'=>4
      ];
      $consonantsDistribution = [
        'n'=>6, 'r'=>6, 't'=>6, 'l'=>4, 's'=>4, 'd'=>4, 'g'=>3, 'b'=>2,
        'c'=>2, 'm'=>2, 'p'=>2, 'f'=>2, 'h'=>2, 'v'=>2, 'w'=>2, 'y'=>2,
        'k'=>1, 'j'=>1, 'x'=>1, 'q'=>1, 'z'=>4, '8'=>2
      ];
      $bagOfVowels = [];
      foreach ($vowelsDistribution as $vowel => $numberOf) {
        for ($i=0; $i<$vowelsDistribution[$vowel]; $i++) {
          array_push($bagOfVowels, $vowel);
        }
      }
      $bagOfConsonants = [];
      foreach ($consonantsDistribution as $consonant => $numberOf) {
        for ($i=0; $i<$consonantsDistribution[$consonant]; $i++) {
          array_push($bagOfConsonants, $consonant);
        }
      }
      // lad(count($bagOfVowels));
      $randomLetters = '';
      for ($i=0; $i<3; $i++) {
        $randomLetters .= $bagOfVowels[rand(0, count($bagOfVowels))];
      }
      for ($i=0; $i<4; $i++) {
        $randomLetters .= $bagOfConsonants[rand(0, count($bagOfConsonants))];
      }
      $randomLetters = str_shuffle($randomLetters);

      return strtoupper($randomLetters);
    }
}
