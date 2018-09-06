<?php

namespace App\ScrabbleLogic;

use App\ScrabbleLogic\FindValidWords;

class FindValidWordsWildcard
{
  public static function findValidWordsWildcard($randomLettersWildcard, $sortedValidWords)
  {
    $randomLettersWildcard = strtolower($randomLettersWildcard);
    $sortedPossibleStrings = self::getSortedStrings($randomLettersWildcard);
    $validWordsWildcard = [];

    foreach ($sortedPossibleStrings as $sortedString) {
      $validWords = FindValidWords::findValidWords($sortedString, $sortedValidWords);

      foreach ($validWords as $validWord) {
        if (!in_array($validWord, $validWordsWildcard)) {
          array_push($validWordsWildcard, $validWord);
        }
      }
    }

    return $validWordsWildcard;
  }


  public static function getSortedStrings($stringWithWildcard)
  {
    $sortedString = FindValidWords::sortString(str_replace('8', '', $stringWithWildcard));
    $sortedPossibleStrings = self::addAtoZ($sortedString);

    return $sortedPossibleStrings;
  }

  public static function addAtoZ($sortedString)
  {
    // returns an array of the ordered strings with each letter in $atoz added
    $atoz = 'abcdefghijklmnopqrstuvwxyz';
    $indexes = self::getAbcIndexes($sortedString);
    $sortedPossibleStrings = [];

    for ($i=0; $i<count($indexes)-1; $i++) {
      for ($j = $indexes[$i]+1; $j <= $indexes[$i+1]; $j++) {
        $possibleString = substr_replace($sortedString, $atoz[$j], $i, 0);

        if (!in_array($possibleString, $sortedPossibleStrings)) {
          array_push($sortedPossibleStrings, $possibleString);
        }
      }
    }

    return $sortedPossibleStrings;
  }



  public static function getAbcIndexes($sortedString)
  {
    // returns an array of the indexes of the letters of $sortedString in $atoz, starting with 0

    $atoz = 'abcdefghijklmnopqrstuvwxyz';
    $indexes = [0];

    for ($i = 0; $i < strlen($sortedString); $i++) {
      // get the indexes of the letters from string in $atoz
      array_push($indexes, strpos($atoz, $sortedString[$i]));
      // insert a up to first letter at the 0 index
    }

    return $indexes;
  }
}
