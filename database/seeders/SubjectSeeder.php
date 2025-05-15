<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $subjects = [
        'الرياضيات',
        'العلوم',
        'اللغة العربية',
        'اللغة الإنجليزية',
        'التربية الإسلامية',
        'التاريخ',
        'الجغرافيا',
        'الفيزياء',
        'الكيمياء',
        'الأحياء'
    ];

    foreach ($subjects as $subject) {
        Subject::create(['Name' => $subject]);
    }
}

}
