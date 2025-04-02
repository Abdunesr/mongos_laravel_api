<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesCollection extends Migration
{
    public function up(): void
    {
        DB::table('employees')->insert([
            'emp_name' => 'Initial',
            'dob' => null,
            'phone' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::connection('mongodb')->table('employees', function ($collection) {
            $collection->index('emp_name');
            $collection->unique('phone');
        });

        DB::table('employees')->where('emp_name', 'Initial')->delete();
    }

    public function down(): void
    {
        Schema::connection('mongodb')->dropIfExists('employees');
    }
}
