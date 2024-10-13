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


    public function submited_by()
    {
        return $this->belongsTo(User::class);
    }

    public function taked_by()
    {
        return $this->belongsTo(User::class);
    }
}