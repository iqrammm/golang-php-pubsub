<?php

namespace App\Services;


class DiskSpaceService
{
    public function handle(array $data)
    {
      $expectedKeys = ['action', 'available_space'];

      foreach ($expectedKeys as $key) {
          if (!array_key_exists($key, $data)) {
              throw new \InvalidArgumentException("Key '$key' is missing in the data array.");
          }
      }
  
      // Check data types of specific keys
      if (!is_string($data['action'])) {
          throw new \InvalidArgumentException("Key 'action' must be a string.");
      }
  
      if (!is_int($data['available_space'])) {
          throw new \InvalidArgumentException("Key 'available_space' must be an integer.");
      }
  
      // Handle the data here
      $action = $data['action'];
      $availableSpace = $data['available_space'];
  
      echo ' [x] Received action: ', $action, "\n";
      echo ' [x] Received available_space: ', $availableSpace, "\n";    
    }
}