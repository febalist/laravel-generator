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

    public static function generateModel($model, $controller = false, $resource = false)
    {
        static::generate(
            'model',
            'app/Http/Controllers/modelClassController.php',
            compact('model')
        );

        if ($controller) {
            static::generateController($model, $resource);
        }
    }

    public static function generateController($model, $resource = false)
    {
        static::generate(
            $resource ? 'controller.resource' : 'controller',
            'app/Http/Controllers/modelClassController.php',
            compact('model')
        );
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

        File::put(base_path($file), $stub);
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

        return $vars;
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
