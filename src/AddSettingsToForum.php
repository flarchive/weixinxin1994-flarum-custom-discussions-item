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

use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Extend;

class AddSettingsToForum
{
    public function __invoke(ForumSerializer $serializer, $forum, array $attributes)
    {
        // 将设置添加到论坛API响应中
        $attributes['custom-discussions-item.enable_author_avatar'] = app('flarum.settings')->get('custom-discussions-item.enable_author_avatar', true);
        $attributes['custom-discussions-item.enable_likes_count'] = app('flarum.settings')->get('custom-discussions-item.enable_likes_count', true);
        $attributes['custom-discussions-item.enable_reply_avatars'] = app('flarum.settings')->get('custom-discussions-item.enable_reply_avatars', true);
        $attributes['custom-discussions-item.max_reply_avatars'] = (int) app('flarum.settings')->get('custom-discussions-item.max_reply_avatars', 5);
        $attributes['custom-discussions-item.avatar_size'] = (int) app('flarum.settings')->get('custom-discussions-item.avatar_size', 24);

        return $attributes;
    }
}
