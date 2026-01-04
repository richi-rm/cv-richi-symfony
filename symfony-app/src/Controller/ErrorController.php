<?php

namespace App\Controller;

use App\Data\DataReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ErrorController extends AbstractController
{
    public function __construct(private DataReader $dataReader)
    {
        $themes = ['blue', 'green', 'honey', 'php', 'red'];
        $this->theme = $themes[array_rand($themes)];
    }

    public function show(Throwable $exception): Response
    {
        if ($exception instanceof NotFoundHttpException) {
            $response = $this->render('notfound.html.twig', [
                'me' => $this->dataReader->read('me'),
                'theme' => $this->theme
            ]);
            return $response;
        }
    }
}
