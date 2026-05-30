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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->unique()->nullable()->after('email_verified_at');
            $table->string('role')->default('admin')->after('password');
            $table->unsignedBigInteger('addedBy')->nullable()->after('role');
            $table->foreign('addedBy')->references('id')->on('users')->nullOnDelete();
            $table->boolean('is_active')->default(1)->after('addedBy');
            $table->string('session_id')->nullable()->after('remember_token');
            $table->softDeletes()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('role');
            $table->dropForeign(['addedBy']);
            $table->dropColumn('addedBy');
            $table->dropColumn('is_active');
            $table->dropColumn('session_id');
            $table->dropSoftDeletes();
        });
    }
};
