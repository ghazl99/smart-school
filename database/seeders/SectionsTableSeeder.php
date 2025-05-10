<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ClassroomSection;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Sections = ['a1','b1', 'c1'];

            $Grade = Grade::where('id',1)->first();
                foreach ($Sections as $section) {
                    $section=Section::create([
                        'Name' => $section,
                        'Status' => 1,
                        'Classroom_id'=>1,
                        'count'=>0,
                        'max_count'=>30
                    ]);

                }





    }
}
