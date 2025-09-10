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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('password');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('dob')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('salary_type')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->boolean('allow_part_deduction')->default(false);
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn([
                'status', 'address', 'city', 'state', 'pincode', 
                'emergency_contact', 'emergency_phone', 'joining_date', 'dob', 
                'aadhar_number', 'pan_number', 'bank_name', 'account_number',
                'ifsc_code', 'salary_type', 'salary', 'allow_part_deduction', 'company_id'
            ]);
        });
    }
};
