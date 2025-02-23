<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueFromEmailInUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Remove the unique constraint from the email column
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique(false)->nullable()->change(); // Remove unique constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Add the unique constraint back to the email column
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->change(); // Add unique constraint back
        });
    }
}
