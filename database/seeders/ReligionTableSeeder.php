<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReligionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $religions = [
            'مسلم',
            'مسيحي',
            'أخرى'
        ];

        foreach ($religions as $R) {
            Religion::create(['Name' => $R]);
        }
    }
}
