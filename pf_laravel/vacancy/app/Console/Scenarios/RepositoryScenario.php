<?php

namespace App\Console\Scenarios;

use Chatway\LaravelCrudGenerator\Core\Base\BaseScenario;
use Chatway\LaravelCrudGenerator\Core\Base\Interfaces\ScenarioInterface;
use Chatway\LaravelCrudGenerator\Core\DTO\ScenarioItem;
use Chatway\LaravelCrudGenerator\Core\Entities\GeneratorForm;
use Chatway\LaravelCrudGenerator\Core\Enums\ScenariosEnum;
use Chatway\LaravelCrudGenerator\Core\Generators\RepositoryGenerator;

class RepositoryScenario extends BaseScenario implements ScenarioInterface
{
    public string $name = ScenariosEnum::MODEL;

    public array $generators = [];

    public function __construct(private GeneratorForm $generatorForm)
    {
    }

    private function addItem($abstract, $options)
    {
        $this->generators[] = new ScenarioItem($abstract, $options);
    }

    public function init(): array
    {
        $this->addItem(RepositoryGenerator::class, [
            'repositoryName' => $this->generatorForm->baseNs . $this->generatorForm->folderNs . '\\'
                                . $this->generatorForm::$REPOSITORY_FOLDER_NAME . '\\'
                                . $this->generatorForm->resourceName . $this->generatorForm::$REPOSITORY_SUFFIX,
            'modelName' => $this->generatorForm->baseNs . $this->generatorForm->folderNs . '\\'
                           . $this->generatorForm::$MODEL_FOLDER_NAME . '\\' . $this->generatorForm->resourceName,
        ]);
        return $this->generators;
    }
}