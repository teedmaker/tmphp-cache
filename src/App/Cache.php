<?php

class Cache
{
    protected $file   = null;
    protected $cache  = null;
    protected $engine = null;

    /**
     * Initializing a new PHP script file
     *
     * @param string $engine
     * @param string $path
     * @param string $cacheDir
     * @param string $cacheName
     */
    public function __construct(TMPHP\Cache\Engine\EngineBase $engine, string $path, string $cacheDir=null, string $cacheName=null) {
        $cacheDir .= substr($cacheDir, -1)==='/'? '': '/';
        $cacheName = $cacheName ?? md5($path);
        $cache     = $cacheDir . $cacheName;
        $this->file  = new TMPHPFile($path);
        $this->cache = new TMPHPFile($cache);
        return $this;
    }

    /**
     * Setting the engine class to manipulates this file.
     *
     * @param string $class
     */
    public function engine(string $class) {
        $this->engine = $class;
        return $this;
    }

    /**
     * Get the file
     *
     * @return void
     */
    public function get() {
        if ($this->verifyHasEdited()) {
            return $this->cache();
        }
        return $this->cache->getPath();
    }

    // protecteds methods

    /**
     * Verify if the file has edited.
     * If the `filemtime` is greater than the cached file, then was.
     *
     * @return boolean
     */
    protected function verifyHasEdited(): bool {
        return $this->file->modified()->isGreaterThan($this->cache);
    }

    /**
     * Cache this file with the engine passed before
     *
     * @return void
     */
    protected function cache() {
        $this->engine = new ${$this->engine}($this->file->getContent());
        return $this;
    }

}
