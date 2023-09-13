<?php

namespace App\Jobs;

use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Jobs\RabbitMQJob as BaseJob;
use App\Services\DiskSpaceService;

class RabbitMQJob extends BaseJob
{

    protected $actionMapping = [
        'disk_space' => DiskSpaceService::class,
        // Incase there is some other kind of action to be processed
    ];

    public function fire()
    {
        $message = json_decode($this->getRawBody(), true);

        if ($message && isset($message['action']) && isset($this->actionMapping[$message['action']])) {
            $serviceClass = $this->actionMapping[$message['action']];
        
            // Create an instance of the service class
            $serviceInstance = new $serviceClass();
            
            // Call the handle method of the service class with the message data
            $serviceInstance->handle($message);
        } else {
            throw new \Exception("Unknown action: {$message['action']}");
        }

        $this->delete();
    }


    public function getName()
    {
        return '';
    }
}
