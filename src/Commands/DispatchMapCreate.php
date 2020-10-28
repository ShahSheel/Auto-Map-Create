<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Sheel\DatabaseLogger\DatabaseLogger;
use Sheel\DatabaseLogger\Markdown;
use Sheel\Map\Jobs\GenerateStaticMap;
use Illuminate\Console\Command;

class DispatchMapCreate extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mapcreate:map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job to the queue worker to update the traffic.';

    /** @var DatabaseLogger $_Logger */
    private $_Logger;

    /** @var Carbon $_Utc */
    private $_Utc;

    /** @var Carbon $_ProjectTimezone */
    private $_ProjectTimezone;

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

        // Initialise this script (set up date/time objects and logger)
        $this->initialise();

        // Try the following methods
        try {

            $this->dispatchMapCreate();
        } // Catch any exception and forward to the logger.
        catch (\Exception $exception) {
            $this->_Logger->createExceptionSubSection($exception);
        }

    }

    /**
     * Sets up the logger and date time objects.
     */
    private function initialise()
    {

        // Create a Logger
        $this->_Logger = \DatabaseLogger::create('Dispatch Panel Router');

        // Immediately log the environment
        $this->_Logger->createEnvironmentSubSection($this);

        // Create date time objects
        $this->_ProjectTimezone = Carbon::now(config('app.project_timezone'));
        $this->_Utc = Carbon::now('UTC');

    }

    /**
     * Dispatches the job and logs the progress.
     */
    private function dispatchMapCreate()
    {
        // Run and log
        $this->_Logger->createSubSection('Dispatching job')
            ->addMarkdown(function (Markdown $Markdown) {

                // Log what we're doing.
                $Markdown->text('Adding the following format to the jobs queue.')
                    ->horizontalRule();

                // Dispatch the job
                dispatch(new GenerateStaticMap())->onQueue('default');

                // Return a success message if no exception is thrown.
                return $Markdown->emphasis('Success');

            })->submit();
    }
}
