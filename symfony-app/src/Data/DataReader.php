<?php

namespace App\Data;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

class DataReader
{
    private string $projectDir;

    public function __construct(
        // Aquí le decimos: "Busca el parámetro kernel.project_dir e inyéctalo"
        #[Autowire('%kernel.project_dir%')] string $projectDir
    ) {
        $this->projectDir = $projectDir;
    }

    public function read(string $dataName): array
    {
        $dataFilePath = $this->projectDir . '/data/' . $dataName . '.json';
        $jsonContent = file_get_contents($dataFilePath);
        $data = json_decode($jsonContent, true); // true para que sea un array

        return $data;
    }
}
