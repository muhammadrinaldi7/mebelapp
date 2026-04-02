<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Brand extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['id' => 2,  'name' => 'Comforta',      'description' => 'Comforta',      'created_at' => '2026-03-26 08:47:04', 'updated_at' => '2026-03-26 08:47:04'],
            ['id' => 3,  'name' => 'Active',        'description' => 'Active',        'created_at' => '2026-04-01 14:00:17', 'updated_at' => '2026-04-01 14:00:17'],
            ['id' => 4,  'name' => 'Bigland',       'description' => 'Bigland',       'created_at' => '2026-04-01 14:00:27', 'updated_at' => '2026-04-01 14:00:27'],
            ['id' => 5,  'name' => 'Importa',       'description' => 'Importa',       'created_at' => '2026-04-01 14:00:36', 'updated_at' => '2026-04-01 14:00:36'],
            ['id' => 6,  'name' => 'Superfit',      'description' => 'Superfit',      'created_at' => '2026-04-01 14:01:28', 'updated_at' => '2026-04-01 14:01:28'],
            ['id' => 7,  'name' => 'Therapedic',    'description' => 'Therapedic',    'created_at' => '2026-04-01 14:01:40', 'updated_at' => '2026-04-01 14:01:40'],
            ['id' => 8,  'name' => 'Olympic',       'description' => 'Olympic',       'created_at' => '2026-04-01 14:02:03', 'updated_at' => '2026-04-01 14:02:03'],
            ['id' => 9,  'name' => 'Astrobox',      'description' => 'Astrobox',      'created_at' => '2026-04-01 14:05:32', 'updated_at' => '2026-04-01 14:05:32'],
            ['id' => 10, 'name' => 'Ayana',         'description' => 'Ayana',         'created_at' => '2026-04-01 14:05:45', 'updated_at' => '2026-04-01 14:05:45'],
            ['id' => 11, 'name' => 'Nudi Interior', 'description' => 'Nudi Interior', 'created_at' => '2026-04-01 14:06:35', 'updated_at' => '2026-04-01 14:06:35'],
            ['id' => 12, 'name' => 'Iman',          'description' => 'Iman',          'created_at' => '2026-04-01 14:06:50', 'updated_at' => '2026-04-01 14:06:50'],
            ['id' => 13, 'name' => 'Habib',         'description' => 'Habib',         'created_at' => '2026-04-01 14:07:00', 'updated_at' => '2026-04-01 14:07:00'],
            ['id' => 14, 'name' => 'Yanor',         'description' => 'Yanor',         'created_at' => '2026-04-01 14:07:18', 'updated_at' => '2026-04-01 14:07:18'],
        ];

        DB::table('brands')->insert($brands);
    }
}
