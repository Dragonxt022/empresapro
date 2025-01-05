<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param int $empresa_id
     * @return void
     */
    public function run()
    {
        $empresa_id = config('app.empresa_id');
        // Array de nomes específicos para as mesas
        $mesas = [
            'Mesa 1',
            'Mesa 2',
            'Mesa 3',
            'Mesa 4',
            'Mesa 5',
            'Mesa 6',
            'Mesa 7',
            'Mesa 8',
            'Mesa 9',
            'Mesa 10',

        ];

        // Loop para criar as mesas com os nomes definidos
        foreach ($mesas as $nome) {
            Mesa::create([
                'empresa_id' => $empresa_id, // Usando o ID da empresa passado como parâmetro
                'nome' => $nome,
                'status' => 'livre', // ou defina qualquer status padrão
            ]);
        }
    }
}
