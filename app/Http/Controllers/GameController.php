<?php

namespace App\Http\Controllers;

use App\Game;
use App\ScrabbleLogic\MakeRandomLetters;
use App\ScrabbleLogic\FindValidWords;
use App\ScrabbleLogic\FindValidWordsWildcard;
use App\Events\StartGame;
use App\Events\EndGame;
use App\Jobs\MakeLettersAndWords;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Start a game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function start(Request $request)
    {
        $latestGame = Game::orderBy('created_at', 'desc')->first();
        $isFirstPlayer = !($latestGame->player2Name === '');

        if ($isFirstPlayer) {
          // Start a new game and wait for opponent
          $game = new Game;
          $game->player1Name = $request->screenName;
          $game->randomLetters = MakeRandomLetters::makeRandomLetters();

          $sortedValidWords = FindValidWords::getSortedValidWords();
          if (strpos($game->randomLetters, '8') === false) {
            $game->validWords = FindValidWords::findValidWords($game->randomLetters, $sortedValidWords);
          }
          else {
            $game->validWords = FindValidWordsWildcard::findValidWordsWildcard($game->randomLetters, $sortedValidWords);
          }

          $game->save();

          return response()->json([
            'game' => $game,
            'firstPlayer' => true,
          ], 201);
        }

        else {
          // There is someone waiting
          $latestGame->player2Name = $request->screenName;
          $latestGame->save();

          event(new StartGame($latestGame));

          return response()->json([
            'game' => $latestGame,
            'firstPlayer' => false,
          ], 201);
        }

    }

  public function end(Request $request)
  {
    if ($request->firstPlayer) {
      usleep(100000);
    }

    $game = Game::find($request->id);

    $solution = '';
    foreach ($request->makeMove as $solutionArray) {
      $solution .= $solutionArray[0] . ' ';
    }
    $score = 0;
    foreach ($request->makeMove as $solutionArray) {
      $score += $solutionArray[1];
    }

    if ($request->firstPlayer) {
      $game->player1Solution = $solution;
      $game->player1Score = $score;
    }
    else {
      $game->player2Solution = $solution;
      $game->player2Score = $score;
    }
    $game->save();

    if ($game->player1Solution !== '' and $game->player2Solution !== '') {
      event(new EndGame($game));
    }
    return response()->json([
      'game' => $game
    ]);
  }
}
