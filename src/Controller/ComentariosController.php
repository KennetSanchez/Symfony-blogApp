<?php

namespace App\Controller;

use App\Entity\Comentarios;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/comentarios', name: 'comentarios_')]
class ComentariosController extends AbstractController
{
    #[Route('/', name: 'base')]
    public function index(): Response
    {
        return $this->render('comentarios/index.html.twig', [
            'controller_name' => 'ComentariosController',
        ]);
    }

    #[Route('/form', name: 'form', methods: 'POST')]
    public function formularioArticulo(Request $request): Response
    {
        $comentario = new Comentarios();

        $form = $this->createFormBuilder($comentario)
            ->add('autor', TextType::class)
            ->add('contenido', TextType::class)
            ->add('respuesta', TextType::class)
            //Parsear a int
            ->add('articulo_id', TextType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comentario = $form->getData();
            // Guardar en la base de datos
            return $this->redirectToRoute('comentarios_base');
        }

        return $this->render('comentarios/formularioComentarios.html.twig', [
            'form' => $form,
        ]);
    }
}
