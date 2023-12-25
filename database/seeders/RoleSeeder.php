<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Roles=["admin","user"];

        foreach($Roles  as $role){
            $newRole = new Role();
            $newRole->name=$role;
            $newRole->save();
        };
        
    }
}
