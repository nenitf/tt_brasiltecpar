<?php

namespace App\Provider;

use App\Core\Provider\ICryptProvider;

class Md5Provider implements ICryptProvider
{
    public function encrypt(string $d): string
    {
        return md5($d);
    }
}
