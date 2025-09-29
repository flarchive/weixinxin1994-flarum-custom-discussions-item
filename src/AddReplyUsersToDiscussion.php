<?php

/*
 * This file is part of custom/discussions-item.
 *
 * Copyright (c) 2024 Custom Developer.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Custom\DiscussionsItem;

use Flarum\Api\Serializer\DiscussionSerializer;
use Flarum\Extend;
use Illuminate\Database\DatabaseManager;

class AddReplyUsersToDiscussion
{
    protected $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function __invoke(DiscussionSerializer $serializer, $discussion, array $attributes)
    {
        // 检查是否启用了回帖用户头像显示
        $enableReplyAvatars = app('flarum.settings')->get('custom-discussions-item.enable_reply_avatars', true);
        
        if (!$enableReplyAvatars) {
            return $attributes;
        }
        
        // 添加回复用户信息
        $replyUsers = [];

        try {
            // 检查讨论是否存在且有效
            if (!$discussion || !isset($discussion->id)) {
                return $attributes;
            }

            // 获取讨论的所有帖子，排除第一个帖子（发帖）
            $posts = $this->db->table('posts')
                ->where('discussion_id', $discussion->id)
                ->where('id', '!=', $discussion->first_post_id)
                ->orderBy('created_at', 'desc')
                ->get();

            // 获取回复用户，去重
            $seenUsers = [];
            foreach ($posts as $post) {
                $userId = $post->user_id;
                if ($userId && !in_array($userId, $seenUsers)) {
                    // 获取用户信息
                    $user = $this->db->table('users')
                        ->where('id', $userId)
                        ->first();
                    
                    if ($user) {
                        // 构建完整的头像URL
                        $avatarUrl = null;
                        if ($user->avatar_url) {
                            // 如果avatar_url是相对路径，添加基础URL
                            if (strpos($user->avatar_url, 'http') === 0) {
                                $avatarUrl = $user->avatar_url;
                            } else {
                                $avatarUrl = app('flarum.config')['url'] . '/assets/avatars/' . $user->avatar_url;
                            }
                        } else {
                            // 使用默认头像
                            $avatarUrl = app('flarum.config')['url'] . '/assets/avatars/default.png';
                        }
                        
                        $replyUsers[] = [
                            'id' => $user->id,
                            'displayName' => $user->display_name,
                            'username' => $user->username,
                            'avatarUrl' => $avatarUrl,
                        ];
                        $seenUsers[] = $userId;
                        
                        // 限制最多返回10个用户
                        if (count($replyUsers) >= 10) {
                            break;
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            // 如果出现错误，记录日志但不中断执行
            \Log::error('Error calculating reply users for discussion ' . ($discussion->id ?? 'unknown') . ': ' . $e->getMessage());
            $replyUsers = [];
        }

        // 确保返回的是有效的数组
        $attributes['replyUsers'] = $replyUsers;

        return $attributes;
    }
}
