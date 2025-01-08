<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use App\Models\Empresa;
use Illuminate\Http\Request;

class AssinaturaController extends Controller
{
    public function index()
    {
        $assinaturas = Assinatura::with('empresa')->get();
        return view('assinaturas.index', compact('assinaturas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'plano' => 'required|string',
            'valor' => 'required|numeric',
            'inicio' => 'required|date',
            'fim' => 'required|date|after:inicio',
            'status' => 'required|in:ativa,expirada,cancelada',
        ]);

        Assinatura::create($data);

        return redirect()->route('assinaturas.index')->with('success', 'Assinatura criada com sucesso!');
    }
}
