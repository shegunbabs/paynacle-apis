<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sage_cloud_data_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('_id')->unique();
            $table->string('type')->unique();
            $table->string('name')->unique();
            $table->string('narration');
            $table->string('image');
            $table->timestamps();
        });

        Schema::create('sage_cloud_data_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('code');
            $table->string('description');
            $table->integer('amount');
            $table->integer('price');
            $table->string('value');
            $table->string('duration');
            $table->timestamps();

            $table->index('type');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sage_cloud_data_providers');
        Schema::dropIfExists('sage_cloud_data_bundles');
    }
};
