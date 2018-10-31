<?php

namespace Febalist\Laravel\Generator\Commands;

use Febalist\Laravel\Generator\Generator;
use Illuminate\Console\Command as BaseCommand;

class Command extends BaseCommand
{
    /** @return Generator */
    protected function generator()
    {
        return new Generator($this->getOutput());
    }
}
