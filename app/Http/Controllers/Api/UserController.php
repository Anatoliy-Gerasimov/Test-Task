<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Services\PostCacheService;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserController extends Controller
{
    /** @var PostCacheService $postCacheService */
    protected $postCacheService;

    public function __construct(PostCacheService $postCacheService)
    {
        $this->postCacheService = $postCacheService;
    }

    /**
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        return response()->json([
            'users' => UserResource::collection(User::all())
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function details(Request $request, User $user): JsonResponse
    {
        /** @var string $orderBy */
        $orderBy = $request->get('orderBy', 'rand');
        /** @var Controller $mutedUsers */
        $mutedUsers = $user->mutedUsers;
        /** @var Collection $mutedIds */
        $mutedIds = $mutedUsers->pluck('id');

        /** @var array $posts */
        $posts = $this
            ->postCacheService
            ->rememberPostsView(
                $orderBy,
                $mutedIds,
                function() use($orderBy, $mutedIds, $request) {
                    $posts = Post::query()
                        ->whereNotIn('user_id', $mutedIds)
                        ->with(['owner'])
                        ->limit(50)
                        ->when(in_array($orderBy, ['desc', 'asc']), function ($query) use($orderBy) {
                            return $query->orderBy('created_at', $orderBy);
                        })
                        ->when(!in_array($orderBy, ['desc', 'asc']), function ($query) {
                            return $query->inRandomOrder();
                        });
                    return PostResource::collection($posts->get())->toArray($request);
                });

        return response()->json([
            'mutedUsers' => UserResource::collection($mutedUsers),
            'posts'      => $posts
        ]);
    }

}
