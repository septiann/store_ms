<?php

namespace App\Enums;

enum PurchaseStatus: string {
    case InProgress = 'W';
    case Complete = 'C';
    case Cancel = 'X';

    public function label(): string {
        return match($this) {
            static::InProgress => 'In Progress',
            static::Complete => 'Complete',
            static::Cancel => 'Cancel',
        };
    }
}
