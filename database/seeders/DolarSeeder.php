<?php

namespace Database\Seeders;

use App\Models\Dolar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DolarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dolar::create([
            "name" => "BCV",
            "amount" => 34.67
        ]);
    }
}
