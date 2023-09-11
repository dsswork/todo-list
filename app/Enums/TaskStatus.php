<?php

namespace App\Enums;

enum TaskStatus: int
{
    case TODO = 0;
    case DONE = 1;

    public function name(): string
    {
        return match ($this) {
            self::TODO => 'Todo',
            self::DONE => 'Done',
        };
    }
}
