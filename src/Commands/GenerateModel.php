<?php

namespace Febalist\Laravel\Generator\Commands;

use Febalist\Laravel\Generator\Generator;
use Illuminate\Console\Command;

class GenerateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:model {name}';

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
        $file = Generator::make('app/modelClass.php', 'model', [
            'model' => $this->argument('name'),
        ]);

        $this->info($file);
    }
}
