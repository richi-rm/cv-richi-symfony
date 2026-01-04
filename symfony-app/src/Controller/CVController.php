<?php

namespace App\Controller;

use App\Data\DataReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Attribute\Route;

class CVController extends AbstractController
{
    private $theme;

    private function createLastPageCookie(string $lastPage): Cookie
    {
        $cookie = Cookie::create('last_page')
            ->withValue($lastPage)
            ->withExpires(new \DateTime('+1 month'))
            ->withPath('/')
            ->withSecure(false)
            ->withHttpOnly(true)
            ->withSameSite('lax');

        return $cookie;
    }

    public function __construct(private DataReader $dataReader)
    {
        $themes = ['blue', 'green', 'honey', 'php', 'red'];
        $this->theme = $themes[array_rand($themes)];
    }

    #[Route('/notfound', name: 'app_notfound')]
    public function notFound(): Response
    {
        return $this->render('notfound.html.twig', [
            'me' => $this->dataReader->read('me'),
            'theme' => $this->theme
        ]);
    }

    #[Route('/cv', name: 'app_cv')]
    public function cv(Request $request): Response
    {
        $experiences = array_values($this->dataReader->read('experiences'));
        foreach ($experiences as $index => $experience) {
            $experiences[$index]['index'] = $index;
        }

        return $this->render('cv.html.twig', [
            'theme' => $this->theme,
            'last_page' => $request->cookies->get('last_page', 'welcome'),
            'me' => $this->dataReader->read('me'),
            // variables necesarias para todas las posibles plantillas
            'education' => $this->dataReader->read('education'),
            'experiences' => $experiences,
            'links' => $this->dataReader->read('links'),
        ]);
    }

    #[Route('/partial/{name}', name: 'app_partial')]
    public function renderPartial(#[Autowire('%kernel.project_dir%')] string $projectDir, string $name): Response
    {
        if (!file_exists($projectDir . '/templates/partials/_' . $name . '.html.twig')) {
            $name = 'welcome';
        }
        $response = $this->render("partials/_{$name}.html.twig", [
            'links' => $this->dataReader->read('links')
        ]);
        $response->headers->setCookie($this->createLastPageCookie($name));

        return $response;
    }

    #[Route('/experiences', name: 'app_experiences')]
    public function renderExperiences(): Response
    {
        $experiences = array_values($this->dataReader->read('experiences'));
        foreach ($experiences as $index => $experience) {
            $experiences[$index]['index'] = $index;
        }
        $response = $this->render("partials/_experiences.html.twig", [
            'experiences' => $experiences
        ]);
        $response->headers->setCookie($this->createLastPageCookie('experiences'));

        return $response;
    }

    #[Route('/experience/{index}', name: 'app_experience')]
    public function renderExperience(int $index): Response
    {
        $experiences = array_values($this->dataReader->read('experiences'));
        $experiencesLastIndex = count($experiences)-1;
        if ($index < 0) {
            $index = 0;
        } elseif ($index > $experiencesLastIndex) {
            $index = $experiencesLastIndex;
        }
        $experience = $experiences[$index];
        $experience['index'] = $index;
        return $this->render("partials/_experience.html.twig", [
            'experience' => $experience,
            'experiencesLastIndex' => $experiencesLastIndex
        ]);
    }

    #[Route('/education', name: 'app_education')]
    public function renderEducation(): Response
    {
        $response = $this->render("partials/_education.html.twig", [
            'education' => $this->dataReader->read('education')
        ]);
        $response->headers->setCookie($this->createLastPageCookie('education'));

        return $response;
    }

    #[Route('/contact', name: 'app_contact')]
    public function renderContact(): Response
    {
        $response = $this->render("partials/_contact.html.twig", [
            'me' => $this->dataReader->read('me')
        ]);
        $response->headers->setCookie($this->createLastPageCookie('contact'));

        return $response;
    }

}
