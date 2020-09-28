<?php

namespace Quinn\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Log;

class BaseLogger extends Logger{
/**
     * Create a custom Monolog instance.
     *
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    
    public function __construct()
    {
        $name = 'logging';
        parent::__construct($name);
    }

    public function __invoke(array $config){
        $logger = new Logger("logging");
        return $logger->pushHandler(new LoggingHandler());
    }

    public function init()
    {
        //set log format and store log in file
        date_default_timezone_set('Asia/Bangkok');
        
        $dateFormat = "[Y-m-d H:i:s]";
        $output = "%datetime% %channel%.%level_name% : %message% %context% %extra%\n";
        $formatter = new LineFormatter($output,$dateFormat);

        $path = 'logs/custom_logs/' . date('Y-m-d') . '.log';
        $stream = new StreamHandler(storage_path($path), Logger::INFO);
        $stream->setFormatter($formatter);
        $this->pushHandler($stream);
    }

    public function info($message, array $context = array()): void
    {
        parent::info($message, $context);
        Log::channel('logging')->info($message);
    }

    public function error($message, array $context = array()): void
    {
        parent::error($message, $context);
        Log::channel('logging')->error($message);
    }

    public function debug($message, array $context = array()): void
    {
        parent::debug($message, $context);
        Log::channel('logging')->debug($message);
    }

    public function emergency($message, array $context = array()): void
    {
        parent::emergency($message, $context);
        Log::channel('logging')->emergency($message);
    }

    public function alert($message, array $context = array()): void
    {
        parent::alert($message, $context);
        Log::channel('logging')->alert($message);
    }

    public function warning($message, array $context = array()): void
    {
        parent::warning($message, $context);
        Log::channel('logging')->warning($message);
    }

    public function notice($message, array $context = array()): void
    {
        parent::notice($message, $context);
        Log::channel('logging')->notice($message);
    }

    public function critical($message, array $context = array()): void
    {
        parent::critical($message, $context);
        Log::channel('logging')->critical($message);
    }

}