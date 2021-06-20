<?php

namespace Sirio\LaravelCommand\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Illuminate\Support\Str;

class ControllerCreateCommand extends GeneratorCommand
{
    protected $signature = 'create:controller 
                            {name : Name of the controller class. Automatically generated Suffix "Controller"}';

    protected $description = 'Custom command for create new resource controller';

    protected $type = 'Controller';

    protected $isSeparate = false;

    protected function getStub()
    {
        $stub = '/../stubs/controller.single.request.stub';

        if ($this->isSeparate) {
            $stub = '/../stubs/controller.full.request.stub';
        }

        return $this->resolveStubPath($stub);
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

        $replace = $this->buildViewReplacements($replace);
        $replace = $this->buildModelReplacements($replace);
        $replace = $this->buildRequestReplacements($replace);

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function buildRequestReplacements(array $replace): array
    {
        if ($this->isSeparate = $this->confirm('Do you wish to create separate Request (Store and Update)?')) {
            foreach (['Store', 'Update'] as $prefixRequest) {

                $requestClass = $this->parseRequest($this->getNameRequest($this->argument('name'), $prefixRequest));
                if (!class_exists($requestClass)) {
                    if ($this->confirm("A {$requestClass} Request does not exist. Do you want to generate it?", true)) {
                        $this->call('make:request', ['name' => $requestClass]);
                    }
                }

                $pref = strtolower($prefixRequest);

                $replace = array_merge($replace, [
                    "DummyFull{$pref}RequestClass" => $requestClass,
                    "{{ {$pref}NamespacedRequest }}" => $requestClass,
                    "{{{$pref}NamespacedRequest}}" => $requestClass,
                    "Dummy{$pref}RequestClass" => class_basename($requestClass),
                    "{{ {$pref}Request }}" => class_basename($requestClass),
                    "{{{$pref}Request}}" => class_basename($requestClass),
                ]);
            }

            return $replace;
        }

        $requestClass = $this->parseRequest($this->getNameRequest($this->argument('name')));

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
        ]);
    }

    protected function buildModelReplacements(array $replace): array
    {
        $modelClass = $this->parseModel($this->argument('name'));
        
        if (! class_exists($modelClass)) {
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
        $path = trim(strtolower($this->argument('name')));

        if (!is_dir($this->viewPath($path))) {
            if ($this->confirm("A {$path} resource views does not exist. Do you want to generate it?", true)) {
                $this->call('create:view', [
                    'name' => $path,
                    '--resource' => true
                ]);
            }
        }

        $path = str_replace('/', '.', $path);

        $variableView = substr($path, strrpos($path, '.') + 1);
        $path = substr($path, 0, strrpos($path, '.'));

        return array_merge($replace, [
            'dummyView' => $path,
            '{{ view }}' => $path,
            '{{view}}' => $path,
            'dummyViewVariable' => $variableView,
            '{{ viewVariable }}' => $variableView,
            '{{viewVariable}}' => $variableView,
        ]);
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
        $nameRequest = ltrim($nameRequest, '\\/');

        $nameRequest = str_replace('/', '\\', $nameRequest);

        $rootNamespace = $this->rootNamespace() . 'Http\\';

        if (Str::startsWith($nameRequest, $rootNamespace)) {
            return $nameRequest;
        }

        return $rootNamespace.'Requests\\'.$nameRequest;
    }

    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    protected function qualifyModel(string $model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return $rootNamespace.'Models\\'.$model;
    }

    protected function getNameInput()
    {
        return trim($this->argument('name')) . 'Controller';
    }

    protected function getNameRequest(string $name, string $prefix = ""): string
    {
        if ($prefix === "") {
            return trim($name) . "Request";
        }

        if(!strrpos($name, '/')) {
            return "{$prefix}{$name}Request";
        }

        $path = substr($name, 0, strrpos($name, '/'));
        $name = substr($name, strrpos($name, '/') + 1);

        return $path . "\\{$prefix}{$name}Request";
    }
}
