<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Companies', function (Blueprint $table) {
            $table->id(); // Auto-incrementing 'company_id' as primary key
            $table->integer('company_code')->unsigned()->unique();
            $table->string('company_name');
            $table->string('company_address')->nullable();
            $table->string('company_logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps(); // 'created_at' and 'updated_at' columns with TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
