<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ErrorController extends AbstractController
{
    public function show(Throwable $exception): RedirectResponse
    {
        // Si el error es un 404 (página no encontrada)
        if ($exception instanceof NotFoundHttpException) {
            // Redirige a la página que quieras (por ejemplo, el home)
            return $this->redirectToRoute('app_notfound');
        }

/*
        // Para otros errores (500, etc.), puedes redirigir o mostrar una página de error
        return $this->redirectToRoute('app_cv');
*/
    }
}
