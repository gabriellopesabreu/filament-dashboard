<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class OrderPart extends Pivot
{
    protected static function boot()
    {
        parent::boot();

        // Ao adicionar uma peça à OS
        static::creating(function ($orderServicePart) {
            $part = Part::find($orderServicePart->part_id);

            if (!$part || $part->quantity < $orderServicePart->quantity) {
                throw new \Exception("Estoque insuficiente para a peça: {$part->name}");
            }

            $part->decrement('quantity', $orderServicePart->quantity);
            Log::info("Peça {$part->name} removida do estoque. Quantidade restante: {$part->quantity}");
        });

        // Ao remover uma peça da OS
        static::deleting(function ($orderServicePart) {
            $part = Part::find($orderServicePart->part_id);

            if ($part) {
                $part->increment('quantity', $orderServicePart->quantity);
                Log::info("Peça {$part->name} devolvida ao estoque. Nova quantidade: {$part->quantity}");
            }
        });
    }
}
