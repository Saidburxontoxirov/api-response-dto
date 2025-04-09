<?php

namespace Burxon\ApiResponseDTO\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class CreateResponseDTOCommand extends GeneratorCommand
{
    protected $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct($files);
    }
    protected $type = 'ApiResponseDTO';

    protected $name = 'make:api-response-dto';

    protected $description = 'Create a new Response API DTO class extending ApiResponseDTO';

    protected function getStub()
    {
        $stubPath = __DIR__ . '/stubs/api-response-dto.stub';

        if (!file_exists($stubPath)) {
            throw new \Exception("Stub file not found at path: {$stubPath}");
        }

        return $stubPath;
    }

    /**
     * Get the default namespace for the class.
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\DTOs';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        $this->info('Response API DTO created successfully!');
    }

    /**
     * Build the class with the given name.
     */

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the console command options.
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the API Response DTO class even if it already exists'],
        ];
    }

    /**
     * Prompt for missing arguments.
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => [
                'What should the ' . $this->type . ' be named?',
                '',
            ],
        ];
    }
}
