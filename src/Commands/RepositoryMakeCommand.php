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

        $stub = '/stubs/repository.model.stub';

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
        $repositoryNamespace = $this->getNamespace($name);

        $replace = [];

        $contractClass = str_replace('Repository', 'Contract', $this->getNameInput());
        $this->call('make:contract', ['name' => $contractClass]);
        $replace = $this->buildRepositoryReplacements($replace, $name);

        $replace["use {$repositoryNamespace}\Repository;\n"] = '';

        $contents = file_get_contents(app_path('Providers\RepositoryServiceProvider.php'));
        $contents = str_replace('protected $repos = [' , 'protected $repos = [
    \App\Contracts\\'.$contractClass.'::class=> \App\Repositories\\'.$this->getNameInput().'::class,', $contents);
        $this->files->put(app_path('Providers\RepositoryServiceProvider.php'), $contents);

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

        if ($this->option('model')) {

            $this->createModel();
        }


    }


    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildContractReplacements(array $replace, $name)
    {
        $contractClass = str_replace('Repository', 'Contract', $name);

        $this->call('make:contract', ['name' => $contractClass]);
        return array_merge($replace, [
//            'DummyFullModelClass' => $contractClass,
//            '{{ namespacedModel }}' => $contractClass,
//            '{{namespacedModel}}' => $contractClass,
//            'DummyModelClass' => class_basename($contractClass),
//            '{{ model }}' => class_basename($contractClass),
//            '{{model}}' => class_basename($contractClass),
//            'DummyModelVariable' => lcfirst(class_basename($contractClass)),
//            '{{ modelVariable }}' => lcfirst(class_basename($contractClass)),
//            '{{modelVariable}}' => lcfirst(class_basename($contractClass)),

        ]);
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

    protected function createModel()
    {
        $model = Str::studly(class_basename($this->argument('name')));
        $model = str_replace('Repository', '', $model);
        $this->call('make:model', [
            'name' => $model,
            '-m' => true,
            '-f' => true,
            '-s' => true,
        ]);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_NONE, 'Create a new model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
        ];
    }
}
