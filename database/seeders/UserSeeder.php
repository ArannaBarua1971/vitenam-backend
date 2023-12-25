<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin=new User();
        $admin->name="aranna";
        $admin->email="arannabaruase@gmail.com";
        $admin->password="aranna1234";
        $admin->save();

        $admin->assignRole('admin');
        
    }
}
