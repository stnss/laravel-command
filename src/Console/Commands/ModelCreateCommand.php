<?php

namespace Sirio\LaravelCommand\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ModelCreateCommand extends GeneratorCommand
{
    
    protected $signature = 'create:model
                            {name : Name of the request class. Automatically generate Soft Delete}
                            {--seed : Create seeder class for given model}
                            {--m|migration : Create migration class for given model}
                            {--factory : Create factory class for given model}';
    
    protected $description = 'Custom command for create model class.';
    
    protected $type = 'Model';
    
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }


    }
    
    protected function getStub()
    {
        return $this->resolveStubPath('/../stubs/model.stub');
    }
    
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }
    
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
    }
}
