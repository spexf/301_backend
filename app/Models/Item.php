<?php

namespace App\Models;

use App\Enums\ItemStatus;
use App\Enums\ItemType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => ItemStatus::class,
        'type' => ItemType::class
    ];

    protected $fillable = [
        'item',
        'description',
        'image',
        'location',
        'type',
        'status',
        'submit_id',
        'take_id',
        'verified'
    ];


    public function submited_by()
    {
        return $this->belongsTo(User::class, 'submit_id', 'id');
    }

    public function taked_by()
    {
        return $this->belongsTo(User::class, 'take_id', 'id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}