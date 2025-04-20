<?php

namespace Database\Seeders;

use App\Models\Admin\ContactGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContactGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactGroup::create([
            'name'      => 'Tecnologia da Informação',
            'status'    => true,
            'token'     => Str::random('64'),
            'created_user' => 1
        ]);
    }
}
