<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'admin','guard_name' => 'api'],
            ['name' => 'teacher','guard_name' => 'api'],
            ['name' => 'student','guard_name' => 'api'],
            ['name' => 'parent','guard_name' => 'api']
        ];

        Role::insert($roles);
    }
}
