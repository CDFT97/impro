<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(CountriesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(DolarSeeder::class);
        // $this->call(ClientsSeeder::class);
        // $this->call(ProviderSeeder::class);
        // $this->call(PurchaseSeeder::class);
        // $this->call(ProductSeeder::class);
        // $this->call(OrderSeeder::class);
    }
}
