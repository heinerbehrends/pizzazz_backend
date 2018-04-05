<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request)
    {
        $latestGame = Game::orderBy('created_at', 'desc')->first();

        if ($latestGame->player2Name === '') {
          // There is someone waiting
          $latestGame->player2Name = $request->screenName;
          $latestGame->save();

          return response()->json([
            'message' => 'You play against ' . $request->screenName
          ], 201);
        }
        else {
          // Start a new game and wait for opponent
          $game = new Game;
          lad($game);
          $game->player1Name = $request->screenName;
          $game->save();
          return response()->json([
            'message' => 'waiting for an opponent'
          ], 201);
        }


        // $responeseData = [
        //   'message' => 'Waiting for an opponent'
        // ]
    }
  public function destroy(Game $game)
    {
        //
    }
}
