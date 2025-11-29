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
            $table->string('avatar')->nullable()->after('email');
            $table->string('resume')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('resume');
            $table->string('phone')->nullable()->after('bio');
            $table->string('location')->nullable()->after('phone');
            $table->string('linkedin_url')->nullable()->after('location');
            $table->string('website')->nullable()->after('linkedin_url');
            $table->text('skills')->nullable()->after('website');
            $table->json('achievements')->nullable()->after('skills');
            $table->integer('years_experience')->nullable()->after('achievements');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('years_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'resume', 'bio', 'phone', 'location', 'linkedin_url', 'website',
                'skills', 'achievements', 'years_experience', 'hourly_rate'
            ]);
        });
    }
};
