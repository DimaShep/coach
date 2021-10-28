<?php namespace Shep\Coach\Console\Commands;

use Illuminate\Console\Command;

/**
 * The CoachCommand class.
 *
 * @package Shep\Coach
 * @author  Dmitriy <dmitriy.shepelenko@gmail.com>
 */
class CoachCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coach';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demo command for Shep\Coach package';

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
        $this->info('Welcome to command for Shep\Coach package');
    }
}
