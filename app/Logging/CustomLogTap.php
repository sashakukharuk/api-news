<?php

namespace App\Logging;

use Monolog\Logger;
use App\Logging\TrimLogProcessor;
    
class CustomLogTap
{
    public function __invoke($logger)
    {
        $monolog = $logger->getLogger();
        $monolog->pushProcessor(new TrimLogProcessor());
    }
}