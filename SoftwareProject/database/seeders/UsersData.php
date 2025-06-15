<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin= User::create([
            "name" => "Admin User",
            "email" => "admin@user",
            "role" => "admin",
            "password" => Hash::make('12345678')
        ]);
        $supplier = User::create([
            "name" => "Supplier User",
            "email" => "supplier@user",
            "role" => "supplier",
            "password" => Hash::make('87654321')
        ]);
    }
}
