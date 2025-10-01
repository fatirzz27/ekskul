<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ekskuls', function (Blueprint $table) {
            $table->foreignId('pembina_id')->nullable()->constrained('users')->cascadeOnDelete()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('ekskuls', function (Blueprint $table) {
            $table->dropForeign(['pembina_id']);
            $table->dropColumn('pembina_id');
        });
    }
};

