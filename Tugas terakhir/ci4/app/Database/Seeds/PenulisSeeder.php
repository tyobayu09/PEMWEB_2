<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PenulisSeeder extends Seeder
{
    public function run()
    {
        /*$data = [
            [
                'name' => 'Yunita Nur Fadhila',
                'address' => 'Jl Gus dur no 150 Jombang',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'name' => 'Nuroiniyatul Alimah',
                'address' => 'Jl Banjar no 10 Jombang',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ],
            [
                'name' => 'Arma Monicha',
                'address' => 'Jl Jogoroto no 17 Jombang',
                'created_at' => Time::now(),
                'updated_at' => Time::now()
            ]
        ];*/

        $faker = \Faker\Factory::create('pt_PT');
        for ($i = 0; $i < 100; $i++) {

            $data = [
                'name' => $faker->name,
                'address' => $faker->address,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'created_at' => Time::createFromTimestamp($faker->unixTime()),
                'updated_at' => Time::now()
            ];

            // Simple query
            //$this->db->query('INSERT INTO penulis (name, address, created_at, updated_at) VALUES(:name:, :address:, :created_at:, :updated_at:)', $data);

            // Using Query Builder
            $this->db->table('penulis')->insert($data);
        }
    }
}
