<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class OrderPart extends Pivot
{
    protected $fillable = [
        'order_id',
        'part_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $attributes = [
        'unit_price' => 0,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
    protected static function boot()
    {
        parent::boot();

        // Ao adicionar uma peça à OS
        static::creating(function ($orderPart) {
            Log::info('Dados recebidos pelo backend:', $orderPart->toArray());
            $part = Part::find($orderPart->part_id);
        
            if ($part->quantity < $orderPart->quantity) {
                throw new \Exception("Estoque insuficiente para a peça: {$part->name}");
            }

            // 🔥 Garante que `unit_price` e `total_price` nunca sejam nulos
            $orderPart->unit_price = $orderPart->unit_price ?? $part->price;
            $orderPart->total_price = $orderPart->total_price ?? ($orderPart->unit_price * $orderPart->quantity);

            $part->decrement('quantity', $orderPart->quantity);
            Log::info("Peça {$part->name} removida do estoque. Quantidade restante: {$part->quantity}");
        });

        // Ao remover uma peça da OS
        static::deleting(function ($orderPart) {
            $part = Part::find($orderPart->part_id);
            
            if ($part) {
                $part->increment('quantity', $orderPart->quantity ?? 0);
                Log::info("Peça {$part->name} devolvida ao estoque. Nova quantidade: {$part->quantity}");
            }
        });

        // 🔥 Protege o `unit_price` de mudanças após salvar no banco
        static::retrieved(function ($orderPart) {
            if (!$orderPart->unit_price) {
                $part = Part::find($orderPart->part_id);
                $orderPart->unit_price = $part->price ?? 0;
            }
        });
    }
}
