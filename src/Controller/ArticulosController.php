<?php

namespace App\Controller;

use App\Entity\Articulos;
use App\Repository\ArticulosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/articulos', name: 'articulos_')]
class ArticulosController extends AbstractController
{
    private ArticulosRepository $articulosRepository;

    public function __construct(ArticulosRepository $ar)
    {
        $this->articulosRepository = $ar;
    }

    #[Route('/base', name: 'base')]
    public function index(): Response
    {
        return $this->render('articulos/index.html.twig', [
            'controller_name' => 'ArticulosController',
        ]);
    }

    private function renderListarArticulos(): Response
    {
        $articulos = $this->articulosRepository->findAll();
        return $this->render('articulos/listarArticulos.html.twig', ['articles' => $articulos]);
    }

    private function renderizarArticuloCreado(Articulos $articulo) : Response{
        return $this->render('articulos/mostrarArticulo.html.twig', ['article' => $articulo]);
    }

    #[Route('/', name: '_todos', methods: 'GET')]
    public function listarArticulos(): Response
    {
        //Respuesta vista
        return new Response($this->renderListarArticulos());

        // Respuesta JSON
        // $articulosJson = $serializer->serialize($articulos, 'json');
        // return new JsonResponse($articulosJson, 200, [], true);
    }

    #[Route('/{id}', name: 'especifico', methods: 'GET')]
    public function visualizarArticulo(int $id, ArticulosRepository $articulosRepository, SerializerInterface $serializer): Response
    {
        $articulos = $articulosRepository->find($id);
        if ($articulos) {
            $articulosJson = $serializer->serialize($articulos, 'json');
            return new JsonResponse($articulosJson, 200, [], true);
        } else {
            return new Response('No existe un artículo con el id: ' . $id);
        }

    }

    #[Route('/', name: 'agregar', methods: 'POST', format: 'json')]
    public function crearArticulo(EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request): Response
    {
        $content = $request->getContent();
        $decoded = json_decode($content);

        $articulo = new Articulos();


        try {
            $articulo->setTitulo($decoded->titulo);
            $articulo->setAutor($decoded->autor);
            $articulo->setContenido($decoded->contenido);
            $articulo->setCategoria($decoded->categoria);
            $articulo->setCreado($decoded->creado);
            $entityManager->persist($articulo);
            $entityManager->flush();
        } catch (Exception $e) {
            return new Response('Hubo un problema al crear el articulo: \n' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new Response($this->renderizarArticuloCreado($articulo));
    }


    #[Route('/{id}/{name}', name: 'modificar_titulo', methods: 'PUT')]
    public function modificarTitulo(EntityManagerInterface $entityManager, ArticulosRepository $articulosRepository, int $id, string $name): Response
    {
        $articulo = $articulosRepository->find($id);

        if (!$articulo) {
            throw $this->createNotFoundException(
                'No hay artículos con el id: ' . $id
            );
        }

        $articulo->setTitulo($name);
        $entityManager->flush();
        return new Response($this->renderListarArticulos());
    }

    #[Route('/modificar/{id}', name: 'modificar', methods: 'PUT', format: 'json')]
    public function modificarArticulo(EntityManagerInterface $entityManager, ArticulosRepository $articulosRepository, int $id, Request $request): Response
    {
        $articulo = $articulosRepository->find($id);

        if (!$articulo) {
            throw $this->createNotFoundException(
                'No hay artículos con el id: ' . $id
            );
        }

        $content = $request->getContent();
        $decoded = json_decode($content);

        $articulo->setTitulo($decoded->titulo ?? $articulo->getTitulo());
        $articulo->setAutor($decoded->autor ?? $articulo->getAutor());
        $articulo->setContenido($decoded->contenido ?? $articulo->getContenido());
        $articulo->setCategoria($decoded->categoria ?? $articulo->getCategoria());
        $articulo->setCreado($decoded->creado ?? date_format($articulo->getCreado(), 'd/m/Y'));

        $entityManager->flush();


        return new Response('Articulo actualizado');
    }

    #[Route('/{id}', name: 'eliminar', methods: 'DELETE')]
    public function eliminarArticulo(EntityManagerInterface $entityManager, ArticulosRepository $articulosRepository, int $id): Response
    {
        $articulo = $articulosRepository->find($id);

        if (!$articulo) {
            throw $this->createNotFoundException(
                'No hay artículos con el id: ' . $id
            );
        }

        $entityManager->remove($articulo);
        $entityManager->flush();
        return new Response($this->renderListarArticulos());
    }

}
