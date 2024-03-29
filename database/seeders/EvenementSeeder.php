<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evenement;


class EvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Evenement::create(
            [
                'nom' => 'Concert du siège à Koudougou',
                'description' => 'Concert Organisé par FasoEvent',
                'datedebut' => '2024-03-24 18:45:18',
                'datefin' => '2024-03-24 23:30:00',
                'lieu' => 'Koudougou',
                'photo' => null,
                'is_active' => false,
                'activite_id' => 1,
                'promoteur_id' => 1,

            ]
         );

         Evenement::create(
            [
                'nom' => 'Concert du siège à Ouaga',
                'description' => 'Concert Organisé par FasoEvent',
                'datedebut' => '2024-03-30 18:45:18',
                'datefin' => '2024-03-30 23:30:00',
                'lieu' => 'Ouaga',
                'photo' => null,
                'is_active' => false,
                'activite_id' => 1,
                'promoteur_id' => 1,


            ]
         );
    }
}
