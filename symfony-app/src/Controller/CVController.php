<?php

namespace App\Controller;

use App\Data\DataReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Attribute\Route;

class CVController extends AbstractController
{

    public function __construct(private DataReader $dataReader)
    {
    }

    #[Route('/cv', name: 'app_cv')]
    public function cv(): Response
    {
        return $this->render('cv.html.twig', [
            'me' => ($me = $this->dataReader->read('me'))
        ]);
    }

    #[Route('/partial/{name}', name: 'app_partial')]
    public function renderPartial(#[Autowire('%kernel.project_dir%')] string $projectDir, string $name): Response
    {
        if (!file_exists($projectDir . '/templates/partials/_' . $name . '.html.twig')) {
            $name = 'profile';
        }
        return $this->render("partials/_{$name}.html.twig");
    }

    #[Route('/experiences', name: 'app_experiences')]
    public function renderExperiences(): Response
    {
        $experiences = array_values($this->dataReader->read('experiences'));
        foreach ($experiences as $index => $experience) {
            $experiences[$index]['index'] = $index;
        }
        return $this->render("partials/_experiences.html.twig", [
            'experiences' => $experiences
        ]);
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
        return $this->render("partials/_education.html.twig", [
            'education' => $this->dataReader->read('education')
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function renderContact(): Response
    {
        return $this->render("partials/_contact.html.twig", [
            'me' => $this->dataReader->read('me')
        ]);
    }

}
