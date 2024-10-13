<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ItemStatus: string implements HasColor, HasIcon, HasLabel
{
    case TAKEN = 'taken';
    case NOTTAKEN = 'not taken';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::TAKEN => 'Taken',
            self::NOTTAKEN => 'Not Taken',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::TAKEN => 'success',
            self::NOTTAKEN => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TAKEN => 'heroicon-o-check-circle',
            self::NOTTAKEN => 'heroicon-o-x-circle',
        };
    }
}
