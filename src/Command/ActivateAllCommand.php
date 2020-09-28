<?php

namespace Quinn\Logging;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Quinn\Logging\Helpers\Publisher;

class ActivateAllCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'logging:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate all command in packages';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        //////////////////////insert route//////////////////////
        if( strpos(file_get_contents("routes/web.php"),'$router->group(["namespace" => "\Quinn\Logging"], function() use ($router) {') !== false) {
        }else{
            //insert route in web.php
            $file = "routes/web.php";
            $fc = fopen($file, "r");
            while (!feof($fc)) {
                $buffer = fgets($fc, 4096);
                $lines[] = $buffer;
            }
            fclose($fc);
            $f = fopen($file, "r+") or die("couldn't open $file");
            $lineCount = count($lines);
            for ($i = 0; $i < $lineCount; $i++) {
                fwrite($f, $lines[$i]);
            }
            fwrite($f,"\n".'$router->group(["namespace" => "\Quinn\Logging"], function() use ($router) {
                $router->get("log", "LoggingController@test");
                $router->get("log/view", ["as"=> "view", "uses"=>"LoggingController@view"]);
                $router->get("log/view/{date}", ["as"=> "show", "uses"=>"LoggingController@show"]);
                $router->post("log/send", ["as"=> "send", "uses"=>"LoggingController@send"]);
                $router->get("log/level/{month}/{level}", ["as"=> "level", "uses"=>"LoggingController@level"]);
            });'."\n");
            fclose($f);
            $this->info('Insert route in web.php');
        }

        //////////////////////set timezone//////////////////////
        $path_to_file = 'routes/web.php';
        $file_contents = file_get_contents($path_to_file);
        $search = '<?php';

        $insert = 'date_default_timezone_set("Asia/Bangkok");';

        $replace = $search."\n".$insert;
        $file_contents = str_replace($search , $replace , $file_contents);
        file_put_contents($path_to_file,$file_contents);

        //////////////////////publish config file//////////////////////

        //create folder if not already have
        $path = 'config';

        if(!is_dir($path)){
            mkdir($path);
        }

        //publish config if not already have file
        if(!file_exists('logging.php')){

            (new Publisher($this))->publishFile(
                realpath(__DIR__.'/../../config/').'/logging.php',
                base_path('config'),'logging.php'
            );
        } else{

        //insert only command if already have file
        $path_to_file = 'config/logging.php';
        $file_contents = file_get_contents($path_to_file);
        $search = '"channels" => [
            ';

        $insert = '"logging" => [
            "driver" => "custom",
            "handler" => Quinn\Logging\LoggingHandler::class,
            "via" => Quinn\Logging\BaseLogger::class,
            "level" => "debug",
        ],';

        $replace = $search."\n".$insert;
        $file_contents = str_replace($search , $replace , $file_contents);
        file_put_contents($path_to_file,$file_contents);
        }
        $this->info('Publish config file/folder');

        //////////////////////publish migrations file//////////////////////

        //publish migrations files
        $this->info('Publishing migrations files...');

        if(!file_exists('2020_08_05_095336_logging.php')){

            (new Publisher($this))->publishFile(
                realpath(__DIR__.'/../../database/migrations/').'/2020_08_05_095336_logging.php',
                database_path('migrations'),
                '2020_08_05_095336_logging.php'
            );
        }

        //run php artisan migrate automatically
        $this->info('Running migrations...');
        $this->call('migrate', ['--force' => true,]);
        $this->comment('Migrations all done!');

        ////////////////////// ALL DONE //////////////////////

        $this->info('Successfully activate all command in packages');
    }
}