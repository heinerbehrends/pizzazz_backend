<?php

namespace App\ScrabbleLogic;

use Illuminate\Support\Facades\File;

class FindValidWords
{
    public static function findValidWords($randomLetters, $sortedValidWords)
    {
      $sortedSubstrings = self::getAllSubstrings(self::sortString($randomLetters));
      $validWords = [];

      foreach ($sortedSubstrings as $sortedSubstring) {

        if (isset($sortedValidWords[$sortedSubstring]) && !in_array($sortedSubstring, $validWords)) {
            array_push($validWords, $sortedValidWords[$sortedSubstring]);
        }
      }

      return $validWords;
    }

    public static function getSortedValidWords()
    {
        $validWordsArray = self::loadWords();
        $sortedValidWords = [];

        foreach ($validWordsArray as $word => $score) {
          $sortedValidWords[self::sortString($word)] = $word;
        }

        return $sortedValidWords;
    }

    public static function getAllSubstrings($string)
    {
      $substrings = [];
      $length = strlen($string);

      for($i=0; $i<$length; $i++){
          for($j=$i; $j<$length+1; $j++){
              $substrings[] = substr($string, $i, $j);
          }
      }

      return $substrings;
    }

    public static function sortString($string)
    {
      $letterArray = str_split($string);
      sort($letterArray);
      $sortedString = implode('', $letterArray);

      return $sortedString;
    }


    public static function loadWords()
    {
      $validWordsJSON = File::get(storage_path() . '/app/word_score_dict.json');

      return json_decode($validWordsJSON, true);
    }


}
