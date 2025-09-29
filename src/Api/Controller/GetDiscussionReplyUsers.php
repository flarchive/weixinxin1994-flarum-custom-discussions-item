<?php

/*
 * This file is part of custom/discussions-item.
 *
 * Copyright (c) 2024 Custom Developer.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Custom\DiscussionsItem\Api\Controller;

use Flarum\Http\RequestUtil;
use Flarum\Discussion\Discussion;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Illuminate\Support\Arr;

class GetDiscussionReplyUsers implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = RequestUtil::getActor($request);
        $discussionId = Arr::get($request->getAttribute('routeParameters'), 'id');
        
        if (!$discussionId) {
            return new JsonResponse(['error' => 'Discussion ID is required'], 400);
        }

        $discussion = Discussion::findOrFail($discussionId);
        
        // 检查权限 - 允许游客查看公开讨论
        if (!$actor->can('view', $discussion)) {
            return new JsonResponse(['error' => 'Permission denied'], 403);
        }

        // 获取讨论的所有帖子
        $posts = $discussion->posts()
            ->where('id', '!=', $discussion->first_post_id) // 排除第一个帖子（发帖）
            ->orderBy('created_at', 'desc')
            ->get();

        // 获取回复用户，去重
        $replyUsers = [];
        $seenUsers = [];

        foreach ($posts as $post) {
            $user = $post->user;
            if ($user && !in_array($user->id, $seenUsers)) {
                $replyUsers[] = [
                    'id' => $user->id,
                    'displayName' => $user->display_name,
                    'username' => $user->username,
                    'avatarUrl' => $user->avatar_url,
                ];
                $seenUsers[] = $user->id;
                
                // 限制最多返回10个用户
                if (count($replyUsers) >= 10) {
                    break;
                }
            }
        }

        return new JsonResponse([
            'data' => $replyUsers
        ]);
    }
}
