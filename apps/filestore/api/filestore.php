<?php
// Use polyfill if we are running on an older PHP where JSON wasn't built in.
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

// Use our own hashing function so we can take advantage of SHA2 if PHP
// version has it (as the hash function).
if (!function_exists('hash')) {
    function calcHash($str) {
        return sha1($str);
    }
}
else {
    function calcHash($str) {
        return hash('sha512', $str);
    }
}

// Check if the name is valid to name a storage item.
function isValidName($name) {
    return preg_match('/^[A-Za-z0-9_.-]+$/', $name) > 0;
}

// Helper to output JSON and optionally surround it with a function call.
function outputJSON($data) {
    if (isset($_GET['callback'])) {
        header('Content-Type: application/javascript');
        echo $_GET['callback'] . '(';
    }
    else {
        header('Content-Type: application/json');
    }
    echo json_encode($data);
    if (isset($_GET['callback']))
        echo ');';
}

// The FileStore class handles directories and files of JSON data.
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
        $all = glob($this->basePath . ($this->useSubDirs || $this->isRoot ? '/':'-') . '*');
        $contents = Array();
        foreach ($all as $file) {
            $file = substr($file, strlen($this->basePath)+1);
            if (strlen($file)>0 && $file[0]!='.' && $file[0]!='_')
                array_push($contents, $file);
        }
        return $contents;
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

    public function remove($name) {
        if (!isValidName($name))
            return;
        unlink($this->getFullPath($name));
    }

    private function getFullPath($name) {
        return $this->basePath . ($this->useSubDirs ? '/':'-') . $name;
    }
}
?>

