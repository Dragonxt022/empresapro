<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',  // Caso o nome seja nulo, retorna 'Não informado'
            'sku' => $this->sku ?? '',  // Substitui null por 'Não informado'
            'barcode' => $this->barcode ?? '',  // Substitui null por 'Não informado'
            'description' => $this->description ?? 'Sem descrição',  // Substitui null por 'Sem descrição'
            'category' => $this->category ? $this->category->name : '?',
            'category_id' => $this->category ? $this->category->id : '?',
            'price' => number_format($this->price ?? 0, 2, ',', '.'),  // Se o preço for nulo, define como 0
            'cost_price' => number_format($this->cost_price ?? 0, 2, ',', '.'),  // Se o preço de custo for nulo, define como 0

            // 'price_nao_formatada' =>$this->price ?? 0,
            // 'cost_price_nao_formatada' =>$this->cost_price ?? 0,

            'stock_quantity' => $this->stock_quantity ?? 0,  // Caso a quantidade de estoque seja nula, define como 0
            'min_stock' => $this->min_stock ?? 0,  // Caso o mínimo de estoque seja nulo, define como 0
            'is_active' => $this->is_active ?? false,  // Caso o status ativo seja nulo, define como false
            'empresa_id' => $this->empresa_id ?? '',  // Substitui null por 'Não informado'
            'is_managed' => $this->is_managed ?? false,  // Caso o gerenciamento seja nulo, define como false
            'image_path' => $this->image_path ?? '',  // Se a imagem for nula, usa uma imagem padrão
            'ncm_code' => $this->ncm_code ?? '',  // Substitui null por 'Não informado'
            'supplier_id' => $this->supplier_id ?? '',  // Substitui null por 'Não informado'
            'expiration_date' => $this->expiration_date ? Carbon::parse($this->expiration_date)->format('d/m/Y') : 'N/D',  // Formata a data ou retorna uma string padrão
            'created_at_date' => Carbon::parse($this->created_at)->toDateString(),
            'created_at_time' => Carbon::parse($this->created_at)->toTimeString(),
            'updated_at_date' => Carbon::parse($this->updated_at)->toDateString(),
            'updated_at_time' => Carbon::parse($this->updated_at)->toTimeString(),

            // Agora apenas retornando os IDs dos relacionamentos, sem incluir diretamente no objeto
            'variations_ids' => $this->variations->pluck('id') ?? [],
        ];
    }

}
