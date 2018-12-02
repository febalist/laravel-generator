<?php

namespace Febalist\Laravel\Generator\Commands;

use Illuminate\Support\Composer;

class GenerateViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:views {model*} {--e|extends=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate views';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Composer $composer)
    {
        foreach ($this->argument('model') as $model) {
            $this->generator()->generateViews(
                $model,
                $this->option('extends')
            );
        }
    }
}
