<?php

namespace Febalist\Laravel\Generator;

use Artisan;
use File;
use Symfony\Component\Console\Output\OutputInterface;

class Generator
{
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function generateModel(
        $model,
        $migration = false,
        $resource = false,
        $controller = false,
        $views = false,
        $extends = null
    ) {

        $this->makeFile(
            'model',
            'app/modelClass.php',
            compact('model')
        );

        if ($migration) {
            $this->generateMigration($model);
        }

        if ($resource) {
            $this->generateResource($model);
        }

        if ($controller) {
            $this->generateController($model, $views, $extends);
        }
    }

    public function generateMigration($model)
    {
        $table = str_plural(snake_case(studly_case($model)));

        Artisan::call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ], $this->output);
    }

    public function generateResource($model)
    {
        $this->makeFile(
            'resource',
            'app/Http/Resources/modelClassResource.php',
            compact('model')
        );
    }

    public function generateController($model, $views = false, $extends = null)
    {
        $this->makeFile(
            'controller',
            'app/Http/Controllers/modelClassController.php',
            compact('model')
        );

        if ($views) {
            $this->generateViews($model, $extends);
        }
    }

    public function generateViews($model, $extends = null)
    {
        foreach (['index', 'show', 'edit'] as $name) {
            $this->makeFile(
                "view.$name",
                "resources/views/modelSnakeCasePlural/$name.blade.php",
                compact('model', 'extends')
            );
        }
    }

    protected function makeFile($stub, $file, $data)
    {
        $content = $this->getStub($stub);
        $vars = $this->getVars($data);

        $content = str_replace(array_keys($vars), array_values($vars), $content);
        $file = str_replace(array_keys($vars), array_values($vars), $file);

        File::makeDirectory(dirname($file), 493, true, true);
        File::put(base_path($file), $content);

        $this->output->writeln("<info>Created $stub:</info> $file");
    }

    protected function getVars($data)
    {
        $vars = [];

        $vars['appNamespace'] = substr(app()->getNamespace(), 0, -1);

        if ($model = $data['model'] ?? null) {
            $vars['modelClass'] = studly_case($model);
            $vars['modelClassPlural'] = str_plural($vars['modelClass']);
            $vars['modelClassFull'] = $vars['appNamespace'].'\\'.$vars['modelClass'];
            $vars['modelCamelCase'] = camel_case($model);
            $vars['modelCamelCasePlural'] = str_plural($vars['modelCamelCase']);
            $vars['modelSnakeCase'] = snake_case($model);
            $vars['modelSnakeCasePlural'] = str_plural($vars['modelSnakeCase']);
        }

        $vars['extendsView'] = $data['extends'] ?? 'layouts.app';

        return array_reverse($vars);
    }

    protected function getStub($stub)
    {
        $path = resource_path("stubs/$stub.stub");
        if (!File::exists($path)) {
            $path = __DIR__."/../stubs/$stub.stub";
        }

        return File::get($path);
    }
}
