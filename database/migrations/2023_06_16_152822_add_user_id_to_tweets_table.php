<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void //カラムを追加したい時
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->cascadeOnDelete();
            // 別テーブルのidを入れたいときはforeignId。
            // foreignId('user（テーブル名）_id（カラム）')->after('id') ←入れたい場所->nullable() ←nullがok->constrained()->cascadeOnDelete() ←user_idが消えたらそのidに紐づく情報も消す;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void //カラムを削除するコード
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id']);
        });
    }
};
