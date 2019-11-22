<?php

use Illuminate\Database\Seeder;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::all()->each(function (\App\Models\User $user) {
            factory(\App\Models\UserAddress::class, random_int(1, 3))->create(['user_id' => $user->id]);
        });
    }
}
