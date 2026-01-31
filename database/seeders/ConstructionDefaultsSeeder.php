<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Family;

class ConstructionDefaultsSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have a Family for construction if not exists
        $family = Family::firstOrCreate(
            ['name' => 'Materiales de Construcción'],
            [
                'code' => 'MAT-CONST',
                'slug' => 'materiales-construccion'
            ]
        );

        Category::firstOrCreate(
            ['name' => 'Materiales de Construcción'],
            [
                'family_id' => $family->id,
                'slug' => 'materiales-construccion'
            ]
        );

        // Ensure we have a Generic Brand
        Brand::firstOrCreate(
            ['name' => 'Genérico'],
            ['logo_path' => null, 'slug' => 'generico']
        );

        Brand::firstOrCreate(
            ['name' => 'Cemex'], // Common construction brand
            ['logo_path' => null, 'slug' => 'cemex']
        );
        Brand::firstOrCreate(
            ['name' => 'Tolteca'], // Common construction brand
            ['logo_path' => null, 'slug' => 'tolteca']
        );
    }
}
