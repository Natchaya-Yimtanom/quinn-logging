<?php

namespace Quinn\Logging;

use Illuminate\Console\Command;

use Quinn\Logging\Logging;

class TruncateTable extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'logging:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all data in database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $files = glob('storage/logs/custom_logs/*');
        foreach($files as $file){
        if(is_file($file))
            unlink($file);
        }
        Logging::truncate();
        $this->info('Successfully delete all data in table and file!');
    }
}