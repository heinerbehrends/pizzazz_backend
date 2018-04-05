<?php

namespace App\Http\Controllers;

use App\Game;
use App\Events\StartGame;
use Illuminate\Http\Request;
// use Pusher\Pusher;
// use Pusher\Laravel\Facades\Pusher;

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

        if ($latestGame->player2Name === '') {
          // There is someone waiting
          $latestGame->player2Name = $request->screenName;
          $latestGame->save();
          // Send event to start the game

          // $options = array(
          //    'cluster' => 'eu',
          //    'encrypted' => true
          //  );
          //  $pusher = new Pusher(
          //    '3d2256d4fd0ec99b3854',
          //    '6f02066e9a8e3a4ff675',
          //    '503491',
          //    $options
          //  );
          //
          //  $data['randomLetters'] = 'abcdefg';
          //  $pusher->trigger('pizzazz', 'start-game', $data);

          event(new StartGame($latestGame));
          // Pusher::trigger('pizzazz', 'start-game', ['randomLetters' => 'abcdefg']);

          return response()->json([
            'message' => 'You play against ' . $request->screenName
          ], 201);
        }
        else {
          // Start a new game and wait for opponent
          $game = new Game;
          $game->player1Name = $request->screenName;
          $game->save();
          // Listen for the second player

          return response()->json([
            'message' => 'waiting for an opponent'
          ], 201);
        }
    }
  public function destroy(Game $game)
    {
        //
    }
}
