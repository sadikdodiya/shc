<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('alt_phone')->nullable()->after('phone');
            $table->text('address')->nullable()->after('alt_phone');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 10)->nullable()->after('state');
            $table->string('emergency_contact')->nullable()->after('pincode');
            $table->string('emergency_phone')->nullable()->after('emergency_contact');
            
            // Employment Details
            $table->date('joining_date')->nullable()->after('emergency_phone');
            $table->date('dob')->nullable()->after('joining_date');
            
            // Identity Proof
            $table->string('aadhar_number', 20)->nullable()->after('dob');
            $table->string('pan_number', 20)->nullable()->after('aadhar_number');
            
            // Bank Details
            $table->string('bank_name')->nullable()->after('pan_number');
            $table->string('account_number', 30)->nullable()->after('bank_name');
            $table->string('ifsc_code', 20)->nullable()->after('account_number');
            
            // Salary Information
            $table->enum('salary_type', ['fixed', 'per_call', 'commission'])->default('fixed')->after('ifsc_code');
            $table->decimal('salary', 12, 2)->default(0)->after('salary_type');
            $table->json('salary_components')->nullable()->after('salary');
            
            // Permissions
            $table->boolean('allow_part_deduction')->default(false)->after('salary_components');
            
            // Status
            $table->boolean('is_active')->default(true)->after('allow_part_deduction');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'alt_phone',
                'address',
                'city',
                'state',
                'pincode',
                'emergency_contact',
                'emergency_phone',
                'joining_date',
                'dob',
                'aadhar_number',
                'pan_number',
                'bank_name',
                'account_number',
                'ifsc_code',
                'salary_type',
                'salary',
                'salary_components',
                'allow_part_deduction',
                'is_active',
            ]);
        });
    }
};
