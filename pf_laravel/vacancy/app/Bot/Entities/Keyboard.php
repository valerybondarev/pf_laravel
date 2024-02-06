<?php

namespace App\Bot\Entities;

class Keyboard
{
    public ?array $keyboard        = null;
    public int    $keyboardRow     = 0;
    public array  $keyboardButtons = [];
}