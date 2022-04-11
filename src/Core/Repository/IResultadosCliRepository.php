<?php

namespace App\Core\Repository;

use App\Core\Model\ResultadoCli;

interface IResultadosCliRepository
{
    public function save(ResultadoCli $e): ResultadoCli;
}
