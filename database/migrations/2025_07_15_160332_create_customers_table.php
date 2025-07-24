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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('ac_number');
            $table->string('full_name');
            $table->string('mobile_number');
            $table->string('email')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['new','in_progress','follow_up','western','hot','closed']);
            $table->foreignId('assigned_employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('sub_department_id')->nullable()->constrained('sub_departments')->onDelete('set null');
            $table->text('complaint_reason')->nullable();
            $table->string('nationality')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_method')->nullable();
            $table->json('documents')->nullable();
            $table->boolean('contacted_other_party')->default(false);
            $table->json('payment_methods')->nullable();
            $table->date('lead_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
