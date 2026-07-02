<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            // Tin nhắn group thì không có receiver_id, nên cho phép null.
            $table->foreignId('receiver_id')->nullable()->change();

            $table->foreignId('group_id')
                ->nullable()
                ->after('receiver_id')
                ->constrained('groups')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');

            $table->foreignId('receiver_id')->nullable(false)->change();
        });
    }
};
