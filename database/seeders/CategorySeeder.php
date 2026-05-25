<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- ADICIONE ESTA LINHA
use Illuminate\Support\Str;          // <-- ADICIONE ESTA LINHA

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $categories = [
            ['name' => 'Serviços de Streaming', 'icon' => 'FaTv'],
            ['name' => 'Cartões de Crédito', 'icon' => 'FaCreditCard'],
            ['name' => 'Vales e Benefícios', 'icon' => 'FaUtensils'],
            ['name' => 'Finanças e Bancos', 'icon' => 'FaUniversity'],
            ['name' => 'E-mail e Comunicação', 'icon' => 'FaEnvelope'],
            ['name' => 'Contas Governamentais', 'icon' => 'FaIdCard'],
            ['name' => 'Redes Sociais', 'icon' => 'FaShareAlt'],
            ['name' => 'Trabalho e Produtividade', 'icon' => 'FaBriefcase'],
            ['name' => 'E-commerce e Compras', 'icon' => 'FaShoppingCart'],
            ['name' => 'Jogos e Entretenimento', 'icon' => 'FaGamepad'],
            ['name' => 'Utilidades e Ferramentas', 'icon' => 'FaKey'],
            ['name' => 'Outros', 'icon' => 'FaOuthers']
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon_identifier' => $category['icon'],
            ]);
        }
    }
}
