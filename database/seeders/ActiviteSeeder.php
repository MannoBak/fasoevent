<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activite;


class ActiviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Activite::create([
            "nom" => "Danse"
        ]);

        Activite::create([
            "nom" => "Foire"
        ]);

        Activite::create([
            "nom" => "Cinéma"
        ]);
    }
}
