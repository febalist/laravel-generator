<?php

namespace Febalist\Laravel\Generator;

use File;

class Generator
{
    protected $file;
    protected $type;
    protected $data;

    public function __construct($file, $type, $data)
    {
        $this->file = $file;
        $this->type = $type;
        $this->data = $data;
    }

    public static function make($file, $type, array $data = [])
    {
        $generator = new static($file, $type, $data);
        return $generator->generate();
    }

    public function generate()
    {
        $stub = $this->getStub();
        $vars = $this->getVars();

        $stub = str_replace(array_keys($vars), array_values($vars), $stub);
        $file = str_replace(array_keys($vars), array_values($vars), $this->file);

        File::put(base_path($file), $stub);

        return $file;
    }

    protected function getVars()
    {
        $vars = [];

        $vars['appNamespace'] = substr(app()->getNamespace(), 0, -1);

        if ($model = $this->data['model'] ?? null) {
            $vars['modelClass'] = studly_case($model);
            $vars['modelFullClass'] = $vars['appNamespace'].'\\'.$vars['modelClass'];
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
