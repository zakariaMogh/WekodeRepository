<?php

namespace Wekode\Repository\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository and contract';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    protected $file;

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;


        if ( file_exists( app_path('Models') ) && is_dir( app_path('Models') ) ) {
            $stub = '/stubs/repository.model.stub';
        }else{
            $stub = '/stubs/repository.model.without.folder.stub';
        }

        return $this->resolveStubPath($stub);
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Repositories';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];
        $replace = $this->buildRepositoryReplacements($replace, $name);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }



    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }
        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('model', true);
            $this->input->setOption('resource', true);
        }

        $this->createContract(); // Create contract class
        $this->linkRepositoryContract(); // Link contract and repository in the ServiceProvider

        if ($this->option('model')) {

            $this->createModel();
        }

        if ($this->option('seed')) {

            $this->createSeed();
        }

        if ($this->option('factory')) {

            $this->createFactory();
        }

        if ($this->option('resource')) {

            $this->createResource();
        }


    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildRepositoryReplacements(array $replace, $name)
    {
        $contractClass = str_replace('Repository', 'Contract', $name);
        $model = str_replace('Repository', '', $name);
        $modelVariable = strtolower($model);
        return array_merge($replace, [
            'DummyModelClass' => class_basename($model),
            '{{ model }}' => class_basename($model),
            '{{model}}' => class_basename($model),
            'DummyContractClass' => class_basename($contractClass),
            '{{ contractClass }}' => class_basename($contractClass),
            '{{contractClass}}' => class_basename($contractClass),
            'DummyModelVariable' => lcfirst(class_basename($modelVariable)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelVariable)),
            '{{modelVariable}}' => lcfirst(class_basename($modelVariable)),

        ]);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    protected function createContract()
    {
        $contract = Str::studly(class_basename($this->argument('name')));
        $contract = str_replace('Repository', 'Contract', $contract);
        $this->call('make:contract', [
            'name' => $contract
        ]);
    }

    protected function createModel()
    {
        $model = Str::studly(class_basename($this->argument('name')));
        $model = str_replace('Repository', '', $model);
        $this->call('make:model', [
            'name' => $model,
            '-m' => true
        ]);
    }

    protected function createSeed()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));
        $seeder = str_replace('Repository', '', $seeder);
        $this->call('make:seeder', [
            'name' => $seeder
        ]);
    }

    protected function createFactory()
    {
        $factory = Str::studly(class_basename($this->argument('name')));
        $factory = str_replace('Repository', '', $factory);
        $this->call('make:factory', [
            'name' => $factory
        ]);
    }

    protected function createResource()
    {
        $resource = Str::studly(class_basename($this->argument('name')));
        $resource = str_replace('Repository', '', $resource);
        $this->call('make:resource', [
            'name' => $resource
        ]);
    }

    protected function linkRepositoryContract()
    {
        $contractClass = str_replace('Repository', 'Contract', $this->getNameInput()); // Get contract class name
        $contents = file_get_contents(app_path('Providers\RepositoryServiceProvider.php')); // Get the content of the Service provider file

        $contents = str_replace('protected $repos = [',
            'protected $repos = [
    \App\Contracts\\'.$contractClass.'::class=> \App\Repositories\\'.$this->getNameInput().'::class,',
            $contents); // Add the line that links the contract and repository

        $this->files->put(app_path('Providers\RepositoryServiceProvider.php'), $contents); // Change the content of the service provider with the new one
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_NONE, 'Create a new model and migration'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a new resource'],
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory and resource for the repository'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
        ];
    }
}
