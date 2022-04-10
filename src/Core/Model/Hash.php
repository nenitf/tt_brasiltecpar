<?php

namespace App\Core\Model;

abstract class Hash
{
    abstract public function getContent(): string;
}
