<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\EconomicGroup;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Employee;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cria 10 usuários
        User::factory(10)->create()->each(function ($user) {
            // Para cada usuário, cria de 1 a 3 grupos econômicos
            $economicGroups = EconomicGroup::factory(rand(1, 3))
                ->create()
                ->each(function ($economicGroup) {
                    // Para cada grupo econômico, cria de 1 a 7 marcas
                    Brand::factory(rand(1, 7))
                        ->create([
                            'economic_group_id' => $economicGroup->id
                        ])
                        ->each(function ($brand) {
                            // Para cada marca, cria de 1 a 20 unidades
                            Unit::factory(rand(1, 20))
                                ->create([
                                    'brand_id' => $brand->id
                                ])
                                ->each(function ($unit) {
                                    // Para cada unidade, cria de 3 a 15 funcionários
                                    Employee::factory(rand(3, 15))
                                        ->create([
                                            'unit_id' => $unit->id
                                        ]);
                                });
                        });
                });

            // Opcional: Associa os grupos econômicos ao usuário se houver uma tabela pivot
            // $user->economicGroups()->attach($economicGroups->pluck('id'));
        });
    }
}
