<?php

namespace App\Core\Provider;

interface ICryptProvider
{
    public function encrypt(string $d): string;
}

