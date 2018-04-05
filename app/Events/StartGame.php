<?php

namespace App\Events;

use App\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StartGame implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // public $game;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
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
      for ($i=0; $i<2; $i++) {
        $randomLetters .= $bagOfVowels[rand(0, count($bagOfVowels))];
      }
      for ($i=0; $i<5; $i++) {
        $randomLetters .= $bagOfConsonants[rand(0, count($bagOfConsonants))];
      }
      $randomLetters = str_shuffle($randomLetters);
      
      return strtoupper($randomLetters);

    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('pizzazz');
    }

    public function broadcastWith()
    {
      // put random letters string here
      return [
        'randomLetters' => $this->makeRandomLetters(),
      ];
    }
}
