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
        Schema::table('sells', function (Blueprint $table) {
            $table->decimal('comission', 5, 2)->default(0)->after('price');
            $table->dropForeign(['comission_id']);
            $table->dropColumn('comission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sells', function (Blueprint $table) {
            $table->foreignId('comission_id')->nullable()->constrained()->onDelete('cascade');
            $table->dropColumn('comission');
        });
    }
};
