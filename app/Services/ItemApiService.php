<?php

namespace App\Services;

use App\Models\Item;
use App\Enums\ItemStatus;
use App\Models\Perumahan;
use App\Models\Announcement;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;


class ItemApiService
{

    public function getItem(): Builder
    {
        return Item::query();
    }

    public function getMyItem($id): Builder
    {
        return Item::query()->where('submit_id', $id);
    }

    public function getTakenItem(): Collection
    {
        return $this->getItem()->where('status', ItemStatus::TAKEN->value)->get();
    }

    public function getNotTakenItem(): Collection
    {
        return $this->getItem()->where('status', ItemStatus::NOTTAKEN->value)->get();
    }

    public function getVerifiedItem(): Collection
    {
        return $this->getItem()->where('verified', true)->get();
    }
    public function getNotVerifiedItem(): Collection
    {
        return $this->getItem()->where('verified', false)->get();
    }

    public function getMyPostedItem($id): Collection
    {
        return $this->getMyItem($id)->get();
    }

}