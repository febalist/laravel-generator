<?php

namespace Febalist\Laravel\Generator\Commands;

use Febalist\Laravel\Generator\Generator;
use Illuminate\Console\Command;

class GenerateViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:views {model} {--e|extends=}';

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
    public function handle()
    {
        Generator::generateViews(
            $this->argument('model'),
            $this->option('extends')
        );
    }
}
