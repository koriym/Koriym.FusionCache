Fusion Cache
============

Memory+File hybrid cache system
--------------------------------

Webサーバーリスタート等で消去されてしまう揮発性のメモリキャッシュと、永続可能なファイルキャッシュ等タイプの違うキャッシュを混ぜて使用するキャッシュです。
コンストラクタでプライマリーキャッシュとセカンダリーキャッシュの取得クロージャを指定します。

```php
<?php

use Koriym\FusionCache\DoctrineCache as FusionCache;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\FilesystemCache;

$cache = new FusionCache(new ApcCache, new FilesystemCache(sys_get_temp_dir()));
$data = $cache->fetch('cache_key')
```

Requirements
------------
 * [doctrine/cache](https://github.com/doctrine/cache)

