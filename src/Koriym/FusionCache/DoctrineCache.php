<?php
/**
 * This file is part of the Koriym.MultiCache
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Koriym\FusionCache;

use Doctrine\Common\Cache\CacheProvider;

class DoctrineCache extends CacheProvider
{
    /**
     * Primary memory cache
     *
     * @var \Doctrine\Common\Cache\CacheProvider
     */
    protected $primaryCache;

    /**
     * Lazy loadable persistent cache instance closure
     *
     * @var callable
     */
    protected $secondaryCache;

    /**
     * @param CacheProvider $primaryCache
     * @param callable      $secondaryCache
     */
    public function __construct(CacheProvider $primaryCache, callable $secondaryCache)
    {
        $this->primaryCache = $primaryCache;
        $this->secondaryCache = $secondaryCache;
    }

    /**
     * @inheritdoc
     */
    protected function doFetch($id)
    {
        $result1 = $this->primaryCache->doFetch($id);
        if ($result1) {
            return $result1;
        }

        $secondaryCache = $this->getSecondCache();
        $result2 = $secondaryCache->doFetch($id);
        if ($result2) {
            $this->primaryCache->doSave($id, $result2);
            return $result2;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function doContains($id)
    {
        $result1 = $this->primaryCache->doContains($id);
        if ($result1) {
            return $result1;
        }

        $secondaryCache = $this->getSecondCache();
        $result2 = $secondaryCache->doContains($id);
        if ($result2) {
            $this->primaryCache->doSave($id, $secondaryCache->doFetch($id));
        }

        return $result2;
    }

    /**
     * @inheritdoc
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        $result = $this->primaryCache->doSave($id, $data, $lifeTime);
        $this->getSecondCache()->doSave($id, $data, $lifeTime);

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function doDelete($id)
    {
        $result = $this->primaryCache->doDelete($id);
        $this->getSecondCache()->doDelete($id);

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function doFlush()
    {
        $result = $this->primaryCache->doFlush();
        $this->getSecondCache()->doFlush();

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function doGetStats()
    {
        return $this->primaryCache->doGetStats();
    }

    /**
     * Return secondary cache instance
     *
     * @return CacheProvider
     */
    private function getSecondCache()
    {
        static $secondaryCache;

        if (! $secondaryCache instanceof CacheProvider) {
            $secondaryCache = call_user_func($this->secondaryCache);
        }

        return $secondaryCache;
    }
}
