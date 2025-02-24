<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'description',
        'status',
        'observations',
        'service_price',
        'total_parts_price',
        'final_total',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class)
        ->withPivot('unit_price', 'quantity', 'total_price')
        ->withTimestamps();
    }

    public function orderParts(): HasMany
    {
        return $this->hasMany(OrderPart::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            foreach ($order->orderParts as $orderPart) {
                if (is_null($orderPart->unit_price)) {
                    $orderPart->unit_price = Part::find($orderPart->part_id)?->price ?? 0;
                }
            }
    
            $order->total_parts_price = $order->orderParts->sum('total_price') ?? 0;
            $order->final_total = $order->total_parts_price + ($order->service_price ?? 0);
        });
        
    }
}
