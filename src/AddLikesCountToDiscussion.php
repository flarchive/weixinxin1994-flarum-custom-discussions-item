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

class AddLikesCountToDiscussion
{
    protected $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    public function __invoke(DiscussionSerializer $serializer, $discussion, array $attributes)
    {
        // 检查是否启用了点赞数显示
        $enableLikesCount = app('flarum.settings')->get('custom-discussions-item.enable_likes_count', true);
        
        if (!$enableLikesCount) {
            return $attributes;
        }
        
        // 计算讨论中所有帖子的点赞总数
        $likesCount = 0;

        try {
            // 检查讨论是否存在且有效
            if (!$discussion || !isset($discussion->id)) {
                return $attributes;
            }
            
            // 使用数据库查询直接计算点赞数量
            $likesCount = $this->db->table('post_likes')
                ->join('posts', 'post_likes.post_id', '=', 'posts.id')
                ->where('posts.discussion_id', $discussion->id)
                ->count();
                
        } catch (\Exception $e) {
            // 如果出现错误，记录日志但不中断执行
            \Log::error('Error calculating likes count for discussion ' . ($discussion->id ?? 'unknown') . ': ' . $e->getMessage());
            $likesCount = 0;
        }
        
        // 确保返回的是有效的整数，并且不破坏原有的attributes结构
        $attributes['likesCount'] = (int) max(0, $likesCount);
        
        return $attributes;
    }
}
