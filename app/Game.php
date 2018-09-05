<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// $wordScoreDict = Storage::get('../app/word_score_dict.json');

class Game extends Model
{
    protected $fillable = [
      'player1', 'player2', 'player1Solution', 'player2Solution',
      'player1Score', 'player2Score'];

      protected $casts = [
         'validWords' => 'array',
     ];

}
