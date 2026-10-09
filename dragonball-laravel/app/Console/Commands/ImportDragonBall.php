<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportDragonBall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dragonball:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'El mundo de Dragon Ball.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Importando datos de Dragon Ball...'); // Muestra un mensaje en la consola indicando que se está importando datos de Dragon Ball
    }
}
