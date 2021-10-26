<?php


namespace Wekode\Repository\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ContractMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:contract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Contract';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Contract';

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

    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $repositoryNamespace = $this->getNamespace($name);
        $replace = [];

        $contractClass = $this->getNameInput();

        $replace = [
            'DummyContractClass' => $contractClass,
            '{{ contractClass }}' => $contractClass,
            '{{contractClass}}' => $contractClass,

        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected function createModel()
    {
        $model = Str::studly(class_basename($this->argument('name')));

        $this->call('make:model', [
            'name' => $model,
            '-m', '-f', '-s'
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/contract.model.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Contracts')) ? $rootNamespace . '\\Contracts' : $rootNamespace;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
//            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, policy, and resource controller for the model'],
//            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
//            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
//            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
//            ['model', 'm', InputOption::VALUE_NONE, 'Create a new model'],
//            ['policy', null, InputOption::VALUE_NONE, 'Create a new policy for the model'],
//            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model'],
//            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
//            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
//            ['api', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be an API controller'],
        ];
    }
}
