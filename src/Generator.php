<?php

namespace Febalist\Laravel\Generator;

use File;

class Generator
{
    protected $type;
    protected $file;
    protected $data;

    public function __construct($type, $file, $data)
    {
        $this->type = $type;
        $this->file = $file;
        $this->data = $data;
    }

    public static function generateModel(
        $model,
        $resource = false,
        $controller = false,
        $views = false,
        $extends = null
    ) {
        static::generate(
            'model',
            app_path('modelClass.php'),
            compact('model')
        );

        if ($resource) {
            static::generateResource($model);
        }

        if ($controller) {
            static::generateController($model, $views, $extends);
        }
    }

    public static function generateResource($model)
    {
        static::generate(
            'resource',
            app_path('Http/Resources/modelClassResource.php'),
            compact('model')
        );
    }

    public static function generateController($model, $views = false, $extends = null)
    {
        static::generate(
            'controller',
            app_path('Http/Controllers/modelClassController.php'),
            compact('model')
        );

        if ($views) {
            static::generateViews($model, $extends);
        }
    }

    public static function generateViews($model, $extends = null)
    {
        foreach (['index', 'show', 'edit'] as $name) {
            static::generate(
                "view.$name",
                resource_path("views/modelSnakeCasePlural/$name.blade.php"),
                compact('model', 'extends')
            );
        }
    }

    protected static function generate($type, $file, $data)
    {
        $generator = new static($type, $file, $data);
        $generator->make();
    }

    public function make()
    {
        $stub = $this->getStub();
        $vars = $this->getVars();

        $stub = str_replace(array_keys($vars), array_values($vars), $stub);
        $file = str_replace(array_keys($vars), array_values($vars), $this->file);

        File::makeDirectory(dirname($file), 493, true, true);
        File::put($file, $stub);
    }

    protected function getVars()
    {
        $vars = [];

        $vars['appNamespace'] = substr(app()->getNamespace(), 0, -1);

        if ($model = $this->data['model'] ?? null) {
            $vars['modelClass'] = studly_case($model);
            $vars['modelClassFull'] = $vars['appNamespace'].'\\'.$vars['modelClass'];
            $vars['modelCamelCase'] = camel_case($model);
            $vars['modelCamelCasePlural'] = str_plural($vars['modelCamelCase']);
            $vars['modelSnakeCase'] = snake_case($model);
            $vars['modelSnakeCasePlural'] = str_plural($vars['modelSnakeCase']);
        }

        $vars['extendsView'] = $this->data['extends'] ?? 'layouts.app';

        return array_reverse($vars);
    }

    protected function getStub()
    {
        $path = resource_path("stubs/$this->type.stub");
        if (!File::exists($path)) {
            $path = __DIR__."/../stubs/$this->type.stub";
        }

        return File::get($path);
    }
}
