<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ItemType: string implements HasColor, HasIcon, HasLabel
{
    case LOST = 'lost';
    case FOUND = 'not taken';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::LOST => 'Lost',
            self::FOUND => 'Not Taken',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::LOST => 'danger',
            self::FOUND => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::LOST => 'heroicon-o-archive-box-arrow-down',
            self::FOUND => 'heroicon-o-archive-box',
        };
    }
}