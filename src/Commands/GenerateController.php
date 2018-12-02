<?php

namespace Febalist\Laravel\Generator\Commands;

use Illuminate\Support\Composer;

class GenerateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:controller {model*} {--b|views} {--e|extends=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controller';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Composer $composer)
    {
        foreach ($this->argument('model') as $model) {
            $this->generator()->generateController(
                $model,
                $this->option('views'),
                $this->option('extends')
            );
        }
    }
}
