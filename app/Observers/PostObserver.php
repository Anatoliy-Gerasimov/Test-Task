<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\PostCacheService;

/**
 * Class PostObserver
 * @package App\Observers
 */
class PostObserver
{
    /** @var PostCacheService $postCacheService */
    protected $postCacheService;

    /**
     * PostObserver constructor.
     * @param PostCacheService $postCacheService
     */
    public function __construct(PostCacheService $postCacheService)
    {
        $this->postCacheService = $postCacheService;
    }

    /**
     * Handle the Post "created" event.
     *
     * @param  Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        $this->postCacheService->flushCache();
    }

    /**
     * Handle the Post "updated" event.
     *
     * @param  Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        $this->postCacheService->flushCache();
    }

    /**
     * Handle the Post "deleted" event.
     *
     * @param  Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        $this->postCacheService->flushCache();
    }

    /**
     * Handle the Post "restored" event.
     *
     * @param  Post  $post
     * @return void
     */
    public function restored(Post $post)
    {
        $this->postCacheService->flushCache();
    }

    /**
     * Handle the Post "force deleted" event.
     *
     * @param  Post  $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        $this->postCacheService->flushCache();
    }
}
