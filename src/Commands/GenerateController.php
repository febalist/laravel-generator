<?php

namespace Febalist\Laravel\Generator\Commands;

use Febalist\Laravel\Generator\Generator;
use Illuminate\Console\Command;

class GenerateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:controller {model} {--r|resource}';

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
    public function handle()
    {
        Generator::generateController(
            $this->argument('model'),
            $this->option('resource')
        );
    }
}
