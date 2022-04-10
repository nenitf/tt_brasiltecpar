<?php

namespace App\Core\Provider;

interface IRandomizerProvider
{
    public function text(int $characters): string;
}
