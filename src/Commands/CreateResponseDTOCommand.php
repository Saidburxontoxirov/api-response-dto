<?php

namespace Burxon\ApiResponseDTO\Commands;


use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;


class CreateResponseDTOCommand extends GeneratorCommand
{

    protected $type = 'ApiResponseDTO';

    protected $name = 'make:api-response-dto';

    protected $description = 'Create a new Response API DTO class extending ApiResponseDTO';

    protected function getStub()
    {
        return __DIR__ . '/stubs/api-response-dto.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\DTOs';
    }

    public function handle()
    {
        parent::handle();

        $this->info('Response API DTO created successfully!');
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $baseDtoNamespace = 'Burxon\ApiResponseDTO';

        return $this->replaceNamespace($stub, $name)
            ->replaceBaseDtoNamespace($stub, $baseDtoNamespace)
            ->replaceClass($stub, $name);
    }

    protected function replaceBaseDtoNamespace($stub, $baseDtoNamespace)
    {
        return str_replace('{{ base_dto_namespace }}', $baseDtoNamespace, $stub);
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the Api Response DTO class'],
        ];
    }

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => [
                'What should the '.($this->type).' be named?',
                match ($this->type) {
                    default => '',
                },
            ],
        ];
    }

}
