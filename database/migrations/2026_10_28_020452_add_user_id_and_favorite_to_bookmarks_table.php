<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            if (! Schema::hasColumn('bookmarks', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            }
            if (! Schema::hasColumn('bookmarks', 'is_favorite')) {
                $table->boolean('is_favorite')->default(false)->after('category');
            }
            if (! Schema::hasColumn('bookmarks', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('is_favorite');
            }
        });

        // Backfill: assign orphan bookmarks to first user or create a default one
        $userId = DB::table('users')->value('id');

        if ($userId === null) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Default User',
                'email' => 'default@linkvault.test',
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('bookmarks')->whereNull('user_id')->update(['user_id' => $userId]);

        Schema::table('bookmarks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookmarks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['is_favorite', 'is_public']);
        });
    }
};
