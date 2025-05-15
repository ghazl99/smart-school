<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RoleSeeder::class,
            BloodTableSeeder::class,
            GenderTableSeeder::class,
            NationalitiesTableSeeder::class,
            ReligionTableSeeder::class,
            SpecializationsTableSeeder::class,
            GradeSeeder::class,
            SubjectSeeder::class,
            ClassroomTableSeeder::class,
            SectionsTableSeeder::class
        ]);
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ])->assignRole('admin');
    }
}
