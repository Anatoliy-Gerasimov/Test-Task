<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class PostCacheService
 * @package App\Services
 */
class PostCacheService
{
    /** @var string $tag */
    protected $tag = 'posts';

    /**
     * PostCacheService constructor.
     */
    public function __construct()
    {
    }

    /**
     * Flush posts cache
     */
    public function flushCache()
    {
        Cache::tags($this->tag)->flush();
    }

    /**
     * @param string $orderBy
     * @param Collection $mutedUsers
     * @param \Closure $closure
     * @return mixed
     */
    public function rememberPostsView(string $orderBy, Collection $mutedUsers, \Closure $closure)
    {
        return Cache::tags([$this->tag])
            ->rememberForever($this->getKeyForPostsView($mutedUsers, $orderBy), $closure);
    }

    /**
     * @param Collection $mutedUsers
     * @param string $direction
     * @return string
     */
    protected function getKeyForPostsView(Collection $mutedUsers, string $direction): string
    {
        return collect([
            $this->tag,
            'except_user' . $mutedUsers->sort()->implode(','),
            $direction
        ])->implode(':');
    }

}
