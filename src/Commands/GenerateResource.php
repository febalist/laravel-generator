<?php

namespace Febalist\Laravel\Generator\Commands;

use Illuminate\Support\Composer;

class GenerateResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:resource {model*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate resource';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Composer $composer)
    {
        foreach ($this->argument('model') as $model) {
            $this->generator()->generateResource(
                $model
            );
        }
    }
}
