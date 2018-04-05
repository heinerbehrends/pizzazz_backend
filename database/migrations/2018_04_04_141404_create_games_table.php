  <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('player1Name');
            $table->string('player2Name')->default('');
            $table->string('player1Solution')->default('');
            $table->string('player2Solution')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return;urn void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
