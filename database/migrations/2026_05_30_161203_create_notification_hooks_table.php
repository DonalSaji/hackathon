<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_hooks', function (Blueprint $table) {
            $table->id();
            $table->integer('notification_template_id')->nullable();
            $table->string('name')->unique();
            $table->string('action');
            $table->string('permission_group');
            $table->text('variables');
            $table->string('permission_name')->nullable();
            $table->string('icon')->nullable();
            $table->string('bg_icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_hooks');
    }
};
