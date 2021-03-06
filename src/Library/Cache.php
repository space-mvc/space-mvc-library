<?php

namespace SpaceMvc\Framework\Library;

use SpaceMvc\Framework\Library\Abstract\CacheAbstract;

/**
 * Class Cache
 * @package SpaceMvc\Framework\Library
 */
class Cache extends CacheAbstract
{
    /**
     * Cache constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $config = $config->setConfig('app')->get()['cache']['redis'];

        if(class_exists('Redis')) {
            $this->cache = new \Redis();
            $this->cache->connect('127.0.0.1', $config['port']);
            $this->cache->select($config['database']);
        }
    }

    /**
     * set
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set(string $key, mixed $value) : Cache
    {
        if(is_array($value)) {
            $value = json_encode($value);
        }

        $this->cache->set($key, $value);

        return $this;
    }

    /**
     * get.
     * @param $key
     * @return mixed
     */
    public function get(string $key) : mixed
    {
        $value = $this->cache->get($key);

        $firstChar = substr($value, 1);
        if($firstChar == '{')
        {
            $value = json_decode($value, 1);
        }

        return $value;
    }

    /**
     * delete
     * @param string $key
     * @return mixed
     */
    public function delete(string $key) : mixed
    {
        return $this->cache->del($key);
    }

    /**
     * clear
     * @return mixed
     */
    public function clear() : mixed
    {
        return $this->cache->flushDB();
    }
}
