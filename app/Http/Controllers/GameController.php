<?php

namespace App\Http\Controllers;

use App\Game;
use App\Events\StartGame;
use App\Events\EndGame;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Start a game.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function save(Request $request)
    {
        $latestGame = Game::orderBy('created_at', 'desc')->first();

        if ($latestGame->player2Name === '') {
          // There is someone waiting
          $latestGame->player2Name = $request->screenName;
          $latestGame->save();

          event(new StartGame($latestGame));

          return response()->json([
            'message' => 'You play against ' . $request->screenName,
            'game' => $latestGame,
            'firstPlayer' => false,
          ], 201);
        }
        else {
          // Start a new game and wait for opponent
          $game = new Game;
          $game->player1Name = $request->screenName;
          $game->randomLetters = $game->makeRandomLetters();
          $game->save();

          return response()->json([
            'message' => 'waiting for an opponent',
            'firstPlayer' => true,
            'game' => $game,
          ], 201);
        }
    }
  public function start(Request $request)
    {
      $latestGame = Game::orderBy('created_at', 'desc')->first();


      return response()->json([
        'id' => $latestGame->id,
        'firstPlayer' => false,
      ], 201);

    }

  public function end(Request $request)
  {
    if ($request->firstPlayer) {
      usleep(100000);
    }
    
    $game = Game::orderBy('created_at', 'desc')->first();

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
