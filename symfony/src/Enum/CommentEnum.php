<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class CommentEnum extends Enum
{
    public const RATE = [
        5 => 'Excellent',
        4 => 'Très bien',
        3 => 'Moyen',
        2 => 'Décevant',
        1 => 'À éviter',
    ];
}
