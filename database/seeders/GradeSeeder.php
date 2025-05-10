<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            'المرحلة الابتدائية',
            'المرحلة المتوسطة',
            'المرحلة الثانوية'
        ];

        foreach ($grades as $grade) {
            Grade::create(['Name' => $grade]);
        }
    }
}
