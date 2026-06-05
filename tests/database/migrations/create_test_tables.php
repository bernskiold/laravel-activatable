<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->activatable();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->activatable();
            $table->inactivatedAt();
            $table->timestamps();
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->activatable('is_enabled');
            $table->timestamps();
        });
    }
};
