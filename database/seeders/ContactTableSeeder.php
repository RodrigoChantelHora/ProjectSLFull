<?php

namespace Database\Seeders;

use App\Models\Admin\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::create([
            'name'      => 'Rodrigo Chantel Hora',
            'email'     => 'rodrigochantel@gmail.com',
            'whatsapp'  => '79999345626',
            'description'   => 'Setor de tecnologia da informação',
            'token'     => Str::random(64),
            'status'    => true,
            'group_id'  => 1 ?? '',
            'created_user'  => 1,
        ]);
    }
}
