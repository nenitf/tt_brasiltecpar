<?php

namespace App\Core\Model;

use App\Repository\ResultadosCliRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultadosCliRepository::class)
 * @ORM\Table(name="resultados_cli")
 */
class ResultadoCli
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    public \DateTime $batch;

    /**
     * @ORM\Column(type="integer")
     */
    public int $bloco;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public string $entrada;

    /**
     * @ORM\Column(type="string", name="chave", length=255)
     */
    public string $key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public string $hash;

    /**
     * @ORM\Column(type="integer")
     */
    public int $tentativas;

    public function __construct(
        ?int $id = null,
        \DateTime $batch,
        int $bloco,
        string $entrada,
        string $key,
        string $hash,
        int $tentativas
    ) {
        $this->batch = $batch;
        $this->bloco = $bloco;
        $this->entrada = $entrada;
        $this->key = $key;
        $this->hash = $hash;
        $this->tentativas = $tentativas;
    }

    public function getId()
    {
        return $this->id;
    }
}
