<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role::truncate();
        Role::insert([
            ['guard_name' => 'web','name' => 'IT'],
            ['guard_name' => 'web', 'name' => 'Employee'],
            ['guard_name' => 'web','name' => 'Operations'],
            ['guard_name' => 'web', 'name' => 'Site Supervisor'],
            ['guard_name' => 'web','name' => 'Data Entry'],
            ['guard_name' => 'web','name' => 'Marketer'],
            ['guard_name' => 'web','name' => 'Quality Control Personnel'],
            ['guard_name' => 'web','name' => 'Customer Service'],
            ['guard_name' => 'web','name' => 'Operation Manager'],
            ]);
    }
}
