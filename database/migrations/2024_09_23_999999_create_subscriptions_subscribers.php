<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("subscriptions_subscribers");
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("subscriptions_subscribers", function (
            Blueprint $table,
        ) {
            $table
                ->foreignId("subscription_id")
                ->constrained("subscriptions")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table
                ->foreignId("subscriber_id")
                ->constrained("subscribers")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }
};
