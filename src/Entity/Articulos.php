<?php

namespace App\Entity;

use App\Repository\ArticulosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints\DateTime;

#[ORM\Entity(repositoryClass: ArticulosRepository::class)]
class Articulos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $autor = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenido = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creado = null;

    #[ORM\Column(length: 255)]
    private ?string $categoria = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
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

    public function getCreado(): ?\DateTimeInterface
    {
        return $this->creado;
    }


    public function setCreado(string $creacionString): static
    {

        try {
            // Attempt to parse the date string using a format string (adjust format if needed)
            $this->creado = date_create_from_format('d/m/Y',$creacionString);
        } catch (Exception $e) {
            // Handle potential parsing errors (e.g., invalid format)
            throw new InvalidArgumentException('Formato incorrecto: ' . $creacionString);
        }
        return $this;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(string $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    //Añadimos relación con Comentarios (1-n)
    /**
     * @ORM\OneToMany(targetEntity="Comentarios", mappedBy="articulo")
     */
    private ArrayCollection $comentarios;
    public function __construct()
    {
        $this->comentarios=new ArrayCollection();
    }

    public function addComentarios(Comentarios $comentarios): void
    {
        $this->comentarios[] = $comentarios;
    }
    public function getComentarios(): Comentarios|\Doctrine\Common\Collections\ArrayCollection
    {
        return $this->comentarios;
    }

}
