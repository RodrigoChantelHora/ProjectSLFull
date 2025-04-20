<?php

namespace Database\Seeders;

use App\Models\Admin\Client as AdminClient;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Nette\Utils\Random;
use Illuminate\Support\Str;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');

        for ($i = 0; $i < 1000; $i++) {
            AdminClient::create([
                'type' => $faker->randomElement([1, 2, 4]),
                'company' => $faker->randomNumber(5, true),
                'name' => $faker->firstName,
                'lastname' => $faker->lastName,
                'register' => Str::random(5) . $faker->cpf(false),
                'name_company' => $faker->company,
                'full_name_company' => $faker->companySuffix,
                'cnpj' => $faker->cnpj(false),
                'email' => Str::random(5) . $faker->unique()->safeEmail,
                'contact' => $faker->phoneNumber,
                'whatsapp' => $faker->cellphoneNumber(false, true),
                'status' => $faker->randomElement(['1', '2']),
                'country' => $faker->country,
                'state' => $faker->stateAbbr,
                'city' => $faker->city,
                'neighborhood' => $faker->word,
                'adress' => $faker->streetAddress,
                'postalcode' => $faker->postcode,
                'token' => Str::random(32),
                'billing' => $faker->randomFloat(2, 100, 5000),
                'issued_date' => $faker->date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
