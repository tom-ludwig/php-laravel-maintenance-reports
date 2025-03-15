<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Project;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add the column as nullable
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable();
        });

        // Get or create a default user
        $defaultUser = User::first() ?? User::factory()->create();

        // Update existing projects
        Project::whereNull('user_id')->update(['user_id' => $defaultUser->id]);

        // Make the column non-nullable and add foreign key constraint
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
