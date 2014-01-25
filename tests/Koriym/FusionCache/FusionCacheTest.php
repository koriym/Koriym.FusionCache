<?php

namespace Koriym\FusionCache;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Tests\Common\Cache\ApcCacheTest;
use Doctrine\Common\Cache\ApcCache;

class FusionCacheTest extends ApcCacheTest
{

    protected function _getCacheDriver()
    {
        do {
            $directory = sys_get_temp_dir() . '/fusion_cache_'. uniqid();
        } while (file_exists($directory));

        return new DoctrineCache(new ApcCache, function () use ($directory) {return new FilesystemCache($directory);});
    }


    public function testSecondaryCacheFetch()
    {
        $cache = $this->_getCacheDriver();
        $cache->save('apc_cache', 1);
        apc_clear_cache('user');
        $this->assertSame($cache->fetch('apc_cache'), 1);
    }

    public function testSecondaryCacheContains()
    {
        $cache = $this->_getCacheDriver();
        $cache->save('apc_cache', 1);
        apc_clear_cache('user');
        $this->assertTrue($cache->contains('apc_cache'));
    }

    /**
     * Return whether multiple cache providers share the same storage.
     *
     * This is used for skipping certain tests for shared storage behavior.
     *
     * @return boolean
     */
    protected function isSharedStorage()
    {
        return false;
    }


}
