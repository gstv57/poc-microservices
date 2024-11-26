<?php

namespace App\Console\Commands;

use App\Jobs\OpportunityJob;
use Illuminate\Console\Command;

class Play extends Command
{
    // Definição do comando
    protected $signature = 'play';

    protected $description = 'Command description';
    public function handle(): void
    {
        // Dados de exemplo a serem passados para o Job
        $payload = ['nome' => 'Gustavo'];

        // Despachar o job
        dispatch(new OpportunityJob($payload));
    }
}
