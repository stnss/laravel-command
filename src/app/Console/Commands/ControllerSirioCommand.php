<?php

namespace Sirio\LaravelCommand\App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Illuminate\Support\Str;

class ControllerSirioCommand extends GeneratorCommand
{
    protected $signature = 'sirio:controller 
                            {name : Name of the controller class. Automatically generated Suffix "Controller"}';


    protected $description = 'Custom command for make new resource controller';

    protected $type = 'Controller';

    protected $isSeparate = false;

    protected function getStub()
    {
        $stub = '/stubs/controller.full.request.stub';

        if ($this->isSeparate) {
            $stub = '/stubs/controller.single.request.stub';
        }

        return $this->resolveStubPath('/../../../stubs/test.stub');
    }

    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        $this->isSeparate = $this->confirm('Do you wish to create separate Request (Store and Update)?');

        $replace = $this->buildRequestReplacements($replace);
        $replace = $this->buildModelReplacements($replace);
        // $replace = $this->buildViewReplacements($replace);

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function buildRequestReplacements(array $replace): array
    {
        $requestClass = $this->parseRequest($this->getNameForType($this->argument('name'), 'Request'));
        if (!class_exists($requestClass)) {
            if ($this->confirm("A {$requestClass} Request does not exist. Do you want to generate it?", true)) {
                $this->call('make:request', ['name' => $requestClass]);
            }
        }

        return array_merge($replace, [
            'DummyFullRequestClass' => $requestClass,
            '{{ namespacedRequest }}' => $requestClass,
            '{{namespacedRequest}}' => $requestClass,
            'DummyRequestClass' => class_basename($requestClass),
            '{{ request }}' => class_basename($requestClass),
            '{{request}}' => class_basename($requestClass),
            'DummyRequestVariable' => lcfirst(class_basename($requestClass)),
            '{{ requestVariable }}' => lcfirst(class_basename($requestClass)),
            '{{requestVariable}}' => lcfirst(class_basename($requestClass)),
        ]);
    }

    protected function buildModelReplacements(array $replace): array
    {
        $modelClass = $this->parseModel($this->argument('name'));

        if (!class_exists(app_path($modelClass))) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $modelClass, '-m' => true]);
            }
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ]);
    }

    protected function buildViewReplacements(array $replace): array
    {

        return [];
    }

    protected function getNameInput()
    {
        return trim($this->argument('name')) . 'Controller';
    }

    protected function getNameForType(string $name, string $type): string
    {
        return trim($name) . trim(ucwords($type));
    }

    protected function parseRequest($name)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $name)) {
            throw new InvalidArgumentException('Request name contains invalid characters.');
        }

        return $this->qualifyRequest($name);
    }

    protected function qualifyRequest(string $nameRequest)
    {
        $nameRequest = ltrim('Http/Requests/' . $nameRequest, '\\/');

        $nameRequest = str_replace('/', '\\', $nameRequest);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($nameRequest, $rootNamespace)) {
            return $nameRequest;
        }

        return is_dir(app_path('Http/Requests'))
            ? $rootNamespace . 'Requests\\' . $nameRequest
            : $rootNamespace . $nameRequest;
    }

    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }
}
