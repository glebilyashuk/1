<?php

class lwpcngAutoloader
{

    /**
     * @var array
     */
    protected $prefixes = [];

    /**
     * @param string $prefix
     * @param string $baseDir
     * @param bool $prepend
     * @return void
     */
    public function addNamespace($prefix, $baseDir, $prepend = false)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR) . '/';

        if (isset($this->prefixes[$prefix]) === false) {
            $this->prefixes[$prefix] = [];
        }

        if ($prepend) {
            array_unshift($this->prefixes[$prefix], $baseDir);
        } else {
            array_push($this->prefixes[$prefix], $baseDir);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function register()
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * @param string $class
     * @return mixed
     */
    public function loadClass($class)
    {
        $prefix = $class;
        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($class, 0, $pos + 1);
            $relativeClass = substr($class, $pos + 1);

            $mappedFile = $this->loadMappedFile($prefix, $relativeClass);
            if ($mappedFile) {
                return $mappedFile;
            }

            $prefix = rtrim($prefix, '\\');
        }
        return false;
    }

    /**
     * @param string $prefix
     * @param string $relativeClass
     * @return mixed Boolean
     */
    protected function loadMappedFile($prefix, $relativeClass)
    {
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }
        foreach ($this->prefixes[$prefix] as $baseDir) {
            $file = $baseDir
                . str_replace('\\', '/', $relativeClass)
                . '.php';
            if ($this->requireFile($file)) {
                return $file;
            }
        }
        return false;
    }

    /**
     * @param string $file
     * @return bool True
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}
