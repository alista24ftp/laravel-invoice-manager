<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Handlers\TaxUpdateHandler;

class UpdateTaxes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:taxes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tax info with actual Canadian taxes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $taxHandler = resolve('App\Handlers\TaxUpdateHandler');
        if($taxHandler->updateTaxes()){
            $this->info('Successfully updated taxes');
        }else{
            $this->error('Failed to update taxes');
        }
    }
}
