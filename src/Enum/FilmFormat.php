<?php

namespace App\Enum;

use App\Enum\Interface\HasLabel;

enum FilmFormat: int implements HasLabel
{
    case VHS = 1;
    case DVD = 2;
    case BLURAY = 3;

    public function label(): string
    {
        return match ($this) {
            self::VHS => 'VHS',
            self::DVD => 'DVD',
            self::BLURAY => 'Blu-ray',
        };
    }
}
