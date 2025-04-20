<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'  => 'Rodrigo Chantel',
            'email' => 'rodrigochantel@gmail.com',
            'password' => Hash::make(75849352),
            'user_token' => Str::random(64),
        ]);
    }
}
