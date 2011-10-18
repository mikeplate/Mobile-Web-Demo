<?php
ini_set('display_errors', 'on');

if (!function_exists('json_encode')) {
    require_once('json.php');

    function json_encode($data) {
        $json = new Services_JSON();
        return $json->encode($data);
    }

    function json_decode($data, $type) {
        $json = new Services_JSON($type ? SERVICES_JSON_LOOSE_TYPE : 0);
        return $json->decode($data);
    }
}

function isValidName($name) {
    return preg_match('/^[A-Za-z0-9_.-]+$/', $name) > 0;
}

class FileStore {
    private $basePath;
    private $baseName;
    private $useSubDirs;
    private $isRoot;

    public function __construct($name, $useSubDirs = true, $root = true) {
        $this->baseName = $name;
        $this->useSubDirs = $useSubDirs;
        $this->isRoot = $root;
        $this->basePath = dirname(realpath(__FILE__)) . '/' . $name;

        if (($this->useSubDirs || $this->isRoot) && !is_dir($this->basePath))
            mkdir($this->basePath);
    }

    public function open($name) {
        if (!isValidName($name))
            return NULL;
        $newName = $this->baseName . ($this->useSubDirs || $this->isRoot ? '/':'-') . $name;
        return new FileStore($newName, $this->useSubDirs, false);
    }

    public function contents() {
        $dir = opendir($this->basePath);
        $all = Array();
        while ($file = readdir($dir)) {
            if (is_file($file) && strpos($file, $baseName))
                array_push($all, $file);
        }
        closedir($dir);
        return $all;
    }
    
    public function exists($name) {
        if (!isValidName($name))
            return false;
        $name = $this->getFullPath($name);
        if (is_file($name) || is_dir($name))
            return true;
        else
            return false;
    }

    public function read($name) {
        if (!isValidName($name))
            return Array();
        $name = $this->getFullPath($name);
        $data = file_get_contents($name);
        return json_decode($data, true);
    }

    public function write($name, $data) {
        if (!isValidName($name))
            return;
        file_put_contents($this->getFullPath($name), json_encode($data));
    }

    private function getFullPath($name) {
        return $this->basePath . ($this->useSubDirs ? '/':'-') . $name;
    }
}
?>

