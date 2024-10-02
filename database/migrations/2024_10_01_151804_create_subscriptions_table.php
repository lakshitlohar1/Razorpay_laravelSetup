<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */


    /**
     * Reverse the migrations.
     */
    public function up()
    {
        // Drop the table if it exists
        Schema::dropIfExists('subscriptions');

        // Create a new subscriptions table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id(); // Automatically creates an auto-incrementing id
            $table->unsignedBigInteger('user_id'); // User ID
            $table->enum('tier', ['silver', 'gold', 'platinum']); // Subscription tier
            $table->timestamp('starts_at')->nullable(); // Start time
            $table->timestamp('ends_at')->nullable(); // End time
            $table->boolean('active')->default(1); // Active status
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
