<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('musica_versoes', function (Blueprint $table) {
            $table->string('fonte_url', 500)->nullable()->after('estrutura_json');
            $table->index('fonte_url');
        });
    }

    public function down(): void
    {
        Schema::table('musica_versoes', function (Blueprint $table) {
            $table->dropIndex(['fonte_url']);
            $table->dropColumn('fonte_url');
        });
    }
};
