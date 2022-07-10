<?php
namespace Application\zLibrary;

class RedisDump {

    const expiry = _DUMP_CACHE_EXP_;
    private $redisInstance;
    private $dump_folder;

        public function __construct($dump_folder = 'SCREDIS'){
          $this->dump_folder = $dump_folder;
        }

        public function getRedis() {
          try {
            if(!$this->redisInstance) {
              $this->redisInstance = new \Redis();
              $this->redisInstance->connect(_REDIS_IP_, _REDIS_PORT_);
            }
            return $this->redisInstance;

          } catch (\Exception $e) {
            $redis_error = $e->getMessage();
            throw new \Application\Exception\Exception("Redis database error: $redis_error", 1);

          }
        }

        public function getDumpName($name) {
          return $this->dump_folder.':'.$name;
        }


        // create redis cache
        public function createDump($data, $dump_name, $expiry = self::expiry, $dump_img = false) {
          $this->getRedis()->set($this->getDumpName($dump_name), serialize($data), ['px'=> $expiry]);
          return $data;
        }

        // delete redis cache
        public function deleteDump($dump_name)
        {
          return $this->getRedis()->del($this->getDumpName($dump_name));
        }


        public function getDump($dump_name) {
          // get cached data
          if($this->getRedis()->exists($this->getDumpName($dump_name))) {
            return unserialize($this->getRedis()->get($this->getDumpName($dump_name)));
          } else return array();
        }

        public function checkDump($dump_name) {
          return (bool) $this->getRedis()->exists($this->getDumpName($dump_name));
        }


	}
?>