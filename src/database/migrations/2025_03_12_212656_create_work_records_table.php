<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->comment('スタッフID');
            $table->date('work_date')->comment('勤務日');
            $table->dateTime('clock_in')->nullable()->comment('出勤時間');
            $table->dateTime('clock_out')->nullable()->comment('退勤時間');
            $table->integer('break_time')->nullable()->comment('休憩時間 (分)');
            $table->text('memo')->nullable()->comment('備考');
            $table->integer('status')->comment('勤怠ステータス :1:出勤中2:退勤');
            $table->dateTime('created_at')->comment('作成日時');
            $table->dateTime('updated_at')->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_records');
    }
};
