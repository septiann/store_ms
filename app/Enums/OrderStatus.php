<?php

namespace App\Enums;

enum OrderStatus: string {
    case Pending = 'W';
    case Complete = 'C';
    case Cancel = 'X';

    public function label(): string {
        return match($this) {
            static::Pending => 'Pending',
            static::Complete => 'Complete',
            static::Cancel => 'Cancel',
        };
    }
}
