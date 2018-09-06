<?php

namespace App\ScrabbleLogic;

use Illuminate\Support\Facades\File;
use App\ScrabbleLogic\Combinations;

class FindValidWords
{
    public static function findValidWords($randomLetters, $sortedValidWords)
    {
      $randomLetters = strtolower($randomLetters);
      $sortedSubstrings = self::getAllSubstrings(self::sortString($randomLetters));
      $validWordsTemp = [];

      foreach ($sortedSubstrings as $sortedSubstring) {

        if (isset($sortedValidWords[$sortedSubstring])) {
            array_push($validWordsTemp, $sortedValidWords[$sortedSubstring]);
        }
      }

      $validWords = [];
      foreach ($validWordsTemp as $wordOrArray) {
        if (is_array($wordOrArray)) {
          foreach ($wordOrArray as $word) {
            array_push($validWords, $word);
          }
        }
        else {
          array_push($validWords, $wordOrArray);
        }
      }

      return $validWords;
    }

    public static function getSortedValidWords()
    {
        // return an array of valdid words for each $sortedString
        // if there are $sortedString with duplicate valid words
        // they are put in an array at the $sortedString
        $validWordsArray = self::loadWords();
        $sortedValidWords = [];

        foreach ($validWordsArray as $word => $score) {
          $sortedString = self::sortString($word);
          if (isset($sortedValidWords[$sortedString])) {
            if (is_array($sortedValidWords[$sortedString])) {
              array_push($sortedValidWords[$sortedString], $word);
            }
            else {
              $sortedValidWords[$sortedString] = array($sortedValidWords[$sortedString],  $word);
            }
          }
          else {
            $sortedValidWords[$sortedString] = $word;
          }
        }

        return $sortedValidWords;
    }

    public static function getAllSubstrings($string)
    {
      $substringsArray = [];
      $length = strlen($string);

      for($i=2; $i<=7; $i++){
        foreach (new Combinations($string, $i) as $substring) {
          if (!in_array($substring, $substringsArray)) {
            array_push($substringsArray, $substring);
          }
        }
      }

      return $substringsArray;
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
