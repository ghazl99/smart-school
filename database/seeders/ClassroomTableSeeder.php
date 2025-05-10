<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassroomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $First_classrooms = [
            'الصف الأول',
            'الصف الثاني',
            'الصف الثالث',
            'الصف الرابع',
            'الصف الخامس',
            'الصف السادس'
        ];
        $First_Grade = Grade::where('id', 1)->first();
        $id = explode(',', $First_Grade->id);

        foreach ($First_classrooms as $First_classroom) {
            Classroom::create([
                'Name' => $First_classroom,
                'Grade_id' => $First_Grade->id
            ]);
        }

        $Second_classrooms = [
            'الصف السابع',
            'الصف الثامن',
            'الصف التاسع',
        ];

        $Second_Grade = Grade::where('id', 2)->first();

        foreach ($Second_classrooms as $Second_classroom) {
            Classroom::create([
                'Name' => $Second_classroom,
                'Grade_id' => $Second_Grade->id
            ]);
        }

        $Third_classrooms = [
            'الصف العاشر',
            'الصف الحادي عشر',
            'الصف الثاني عشر',
        ];

        $Third_Grade = Grade::where('id', 3)->first();

        foreach ($Third_classrooms as $Third_classroom) {
            Classroom::create([
                'Name' => $Third_classroom,
                'Grade_id' => $Third_Grade->id
            ]);
        }
    }
}
