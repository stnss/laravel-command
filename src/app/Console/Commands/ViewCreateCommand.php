<?php

namespace Sirio\LaravelCommand\App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;
use Illuminate\Support\Str;

class ViewCreateCommand extends GeneratorCommand
{
    protected $signature = 'create:view 
                            {name : Name of the view blade.}
                            {--resource : Create resources blade file for given name}
                            {--extends= : Automatically adding extends blade directive}
                            {--section=* : Automatically adding sections blade directive}';

    protected $description = 'Custom command for create new view';

    protected $type = 'View';
    
    public function handle()
    {
        if($this->option('resource')) {
            foreach(['index', 'create', 'show', 'edit'] as $resName) {
                $this->createView($this->argument('name') . "/{$resName}");
            }
        } else {
            $this->createView($this->argument('name'));
        }

        $this->info($this->type.' created successfully.');
    }

    protected function getStub() {
        $stub = '/../../../stubs/view.stub';

        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function createView($name) {
        $path = $this->viewPath(
            str_replace('.', '/', strtolower($name)).'.blade.php'
        );

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        $stub = $this->files->get($this->getStub());

        $this->buildView($stub);
        $this->files->put($path, $stub);
    }

    protected function buildView(&$stub) {
        $placeholders = [
            ['dummyExtends', 'dummySections'],
            ['{{extends}}', '{{sections}}'],
            ['{{ extends }}', '{{ sections }}']
        ];

        foreach($placeholders as $placeholder) {
            $stub = str_replace(
                $placeholder,
                [$this->getExtends(), $this->getSection()],
                $stub);
        }
    }

    protected function getExtends() {
        $extends = "";

        if($this->option('extends')) {
            $extends .= "@extends('{$this->option('extends')}')";
        }

        return $extends;
    }

    protected function getSection() {
        $sections = "";
        foreach($this->option('section') as $index => $section) {
            $sections .= "@section('{$section}')\n\n@endsection";
            if($index != Count($this->option('section')) - 1) {
                $sections .= "\n\n";
            }
        }

        return $sections;
    }
}
