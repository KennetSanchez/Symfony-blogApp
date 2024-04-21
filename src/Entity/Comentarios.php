<?php

namespace App\Entity;

use App\Repository\ComentariosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComentariosRepository::class)]
class Comentarios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $autor = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenido = null;

    #[ORM\Column]
    private ?int $respuesta = null;

    #[ORM\Column]
    private ?int $articulo_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): static
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getRespuesta(): ?int
    {
        return $this->respuesta;
    }

    public function setRespuesta(int $respuesta): static
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    public function getArticuloId(): ?int
    {
        return $this->articulo_id;
    }

    public function setArticuloId(int $articulo_id): static
    {
        $this->articulo_id = $articulo_id;

        return $this;
    }

    //Relación N a 1 con Artículos
    /**
     * @ORM\ManyToOne (targetEntity="Articulos", inversedBy="Comentarios")
     * @ORM\JoinColumn (name="articulo_id", referencedColumnName="id")
     * @return integer
     */
    private Articulos $articulo;
    public function setArticulo(Articulos $articulo): void
    {
        $this->articulo= $articulo;
    }
    public function getArticulo()
    {
        return $this->articulo;
    }

}
