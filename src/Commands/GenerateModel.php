<?php

namespace Febalist\Laravel\Generator\Commands;

use Illuminate\Support\Composer;

class GenerateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:model {model*} {--m|migration} {--r|resource} {--c|controller} {--b|views} {--e|extends=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Composer $composer)
    {
        foreach ($this->argument('model') as $model) {
            $this->generator()->generateModel(
                $model,
                $this->option('migration'),
                $this->option('resource'),
                $this->option('controller'),
                $this->option('views'),
                $this->option('extends')
            );
        }

        $composer->dumpAutoloads();
    }
}
