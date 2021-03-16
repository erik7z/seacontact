<?php
namespace Application\zLibrary;

class Dump {

    const expiry = _DUMP_CACHE_EXP_;
    public $dump_folder;

        public function __construct($dump_folder = _ROOT_.'data/dump_cache'){
            $this->createDir($dump_folder);
            $this->dump_folder = $dump_folder;
        }


        public function setDumpFolder($folder)
        {
            $this->createDir($folder);
            $this->dump_folder = $folder;
        }

        public function getDumpFolder()
        {
            return $this->dump_folder;
        }

        public function createDump($data, $dump_name, $expiry = self::expiry, $dump_img = false) {
            $folder = $this->dump_folder.'/'.$dump_name;
            $this->createDir($folder);
            if($dump_img) $data = $this->dumpImg($data, $folder);

            file_put_contents($folder.'/'.$dump_name.'.dmp', serialize($data));
            file_put_contents($folder.'/'.$dump_name.'.time', time());
            file_put_contents($folder.'/'.$dump_name.'.exp', $expiry);
            return $data;
        }

        public function deleteDump($dump_name)
        {
            $folder = $this->dump_folder.'/'.$dump_name;
            z_delete([$folder.'/'.$dump_name.'.dmp', $folder.'/'.$dump_name.'.time', $folder.'/'.$dump_name.'.exp']);
            @rmdir($folder);
        }

        // добавляем новые данные в конец дампа (не рекомендуется исполозовать вместе с updateDump)
        public function addToDump($data, $dump_name, $expiry = self::expiry, $dump_img = false) {
            $old_dump = ($d = $this->getDump($dump_name))? $d : array();
            $new_dump = array_merge($old_dump, $data);
            return $this->createDump($new_dump, $dump_name, $expiry, $dump_img);
        }

        // обновляем дамп, перезаписываются соответсвующие ключи в массиве
        public function updateDump($data, $dump_name, $expiry = self::expiry, $dump_img = false) {
            $old_dump = ($d = $this->getDump($dump_name))? $d : array();
            $new_dump = array_replace($old_dump, $data);
            return $this->createDump($new_dump, $dump_name, $expiry, true);
        }

        public function getDump($dump_name) {
            $file = $this->dump_folder.'/'.$dump_name.'/'.$dump_name.'.dmp';
             if(file_exists($file)) {
                return unserialize(file_get_contents($file));
            } else return array();
        }

        public function cleanDump($dump_name){
            return $this->createDump(array(), $dump_name);
        }

        public function checkDump($dump_name) {
            $dump = $dump_name.'/'.$dump_name.'.time';
            $exp = $dump_name.'/'.$dump_name.'.exp';
            if(file_exists($this->dump_folder.'/'.$dump)) {
                $dump_time = file_get_contents($this->dump_folder.'/'.$dump);
                $expiry = (file_exists($this->dump_folder.'/'.$exp))? file_get_contents($this->dump_folder.'/'.$exp) : self::expiry;
                $diff = (time() - $dump_time);
                if($diff > $expiry) {
                    $this->deleteDump($dump_name);
                    return false;
                }
               return true;
            }
            return false;
        }

        // выкачиваются и сохраняются картинки по ссылке в массиве
       public function dumpImg($dataArray, $folder) {
             foreach ($dataArray as $key => $value) {
                if(is_array($value)) {
                    $dataArray[$key] = $this->dumpImg($value, $folder);
                    continue;
                }
               if(preg_match("/.?\/(?'name'[a-zA-Z0-9_-]+\.(?'ext'jpg|jpeg|png|gif))$/i", $value, $match)) {
                 $file_name = $folder.'/'.$match['name'];
                if(!file_exists($file_name)) {
                    if(file_exists($value)) {
                        $img = file_get_contents($value);
                        file_put_contents($file_name, $img);
                    }
                }
                $dataArray[$key] = '/'.$file_name;
               }
            }
            return $dataArray;
        }

        private function createDir($dir) {

            if(!is_dir($dir)){
                mkdir($dir, 0777);
                chmod($dir, 0777);
            }

        }

	}
?>