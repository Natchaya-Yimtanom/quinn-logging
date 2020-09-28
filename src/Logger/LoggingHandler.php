<?php

namespace Quinn\Logging;

use DB;
use Illuminate\Support\Facades\Auth;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class LoggingHandler extends AbstractProcessingHandler{
/**
 *
 * Reference:
 * https://github.com/markhilton/monolog-mysql/blob/master/src/Logger/Monolog/Handler/MysqlHandler.php
 */
    public function __construct($level = Logger::DEBUG, $bubble = true) {
        
        $this->table = 'logging';
        parent::__construct($level, $bubble);
    }

    protected function write(array $record):void
    {
        //set log format and store log in database
        $date = substr($record['datetime'],0,10);
        $time = substr($record['datetime'],11,8);

        if($record['level']==400){
            $allmessage = explode('Stack',$record['message']);
            $message = $allmessage[0];
            $stack = substr($record['message'],strpos($record['message'],"Stack"),strlen($record['message']));
        } else{
            $message = $record['message'];
            $stack = null;
        }

        $data = array(
           'user'          => get_current_user(),
           'level'         => $record['level'],
           'level_name'    => $record['level_name'],
           'message'       => $message,
           'stack'         => $stack,
           'channel'       => $record['channel'],
            'date'         => $date,
            'time'         => $time,
            'context'       => json_encode($record['context']),
           'extra'         => json_encode($record['extra']),
           'remote_addr'   => $_SERVER['REMOTE_ADDR'],
           'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
       );

       DB::connection()->table($this->table)->insert($data);
    }

}