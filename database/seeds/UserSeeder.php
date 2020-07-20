<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 25) as $index) {
            $type = $faker->randomElement(['admin', 'author']);

            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->email,
                'type' => $type,
                'password' => Hash::make('aaaaaaaa'),
                'created_at' => date('Y-m-d G:i:s'),
                'updated_at' => date('Y-m-d G:i:s')
            ]);
        }
    }
}
