<?php

namespace Database\Seeders;

// use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'is_admin' => true,
                'status' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        $user = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'is_admin' => false,
                'status' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            ]
        );
        if ($user->addresses()->count() < 2) {

            $user->addresses()->delete(); // clean old if any

            $user->addresses()->createMany([
                [
                    'building_no' => '12A',
                    'street' => 'Anna Nagar Main Road',
                    'city' => 'Chennai',
                    'district' => 'Chennai',
                    'state' => 'Tamil Nadu',
                    'postal_code' => '600040',
                    'country' => 'India',
                    'is_default' => true,
                ],
                [
                    'building_no' => '45B',
                    'street' => 'Velachery Road',
                    'city' => 'Chennai',
                    'district' => 'Chennai',
                    'state' => 'Tamil Nadu',
                    'postal_code' => '600042',
                    'country' => 'India',
                    'is_default' => false,
                ],
            ]);
        }
    }
}
