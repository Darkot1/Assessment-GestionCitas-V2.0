<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Availability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);


        $patient = User::create([
            'name' => 'Patient User',
            'email' => 'patient@example.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

        Patient::create([
            'user_id' => $patient->id,
            'date_of_birth' => '1990-01-01',
            'phone' => '123456789',
            'address' => '123 Patient St.',
        ]);

        $doctor = User::create([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        $doctorAccount = Doctor::create([
            'user_id' => $doctor->id,
            'specialty' => 'Cardiology',
            'phone' => '987654321',
            'address' => '456 Doctor Ave.',
        ]);

        Availability::create([
            'doctor_id' => $doctorAccount->id,
            'start_date' => '2024-12-30',
            'end_date' => '2024-12-30',
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => 'available',
        ]);

        Availability::create([
            'doctor_id' => $doctorAccount->id,
            'start_date' => '2024-12-30',
            'end_date' => '2024-12-30',
            'start_time' => '10:45:00',
            'end_time' => '11:30:00',
            'status' => 'available',
        ]);
    }
}
