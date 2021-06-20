<?php

namespace Sirio\LaravelCommand\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class RequestCreateCommand extends GeneratorCommand
{
    protected $signature = 'create:request
                            {name : Name of the request class.}';

    protected $description = 'Custom command for create request class.';
    
    protected $type = 'Request';

    protected function getStub() {
        return $this->resolveStubPath('/../stubs/request.stub');
    }
    
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
                        ? $customPath
                        : __DIR__.$stub;
    }
    
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Requests';
    }
}
