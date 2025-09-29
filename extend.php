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

use Flarum\Extend;
use Flarum\Api\Serializer\DiscussionSerializer;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),

    new Extend\Locales(__DIR__.'/locale'),

        (new Extend\ApiSerializer(DiscussionSerializer::class))
            ->attributes(AddLikesCountToDiscussion::class)
            ->attributes(AddReplyUsersToDiscussion::class),

        (new Extend\ApiSerializer(\Flarum\Api\Serializer\ForumSerializer::class))
            ->attributes(AddSettingsToForum::class),

        // 注册API路由（保留以备需要）
        (new Extend\Routes('api'))
            ->get('/discussions/{id}/reply-users', 'discussions.reply-users', \Custom\DiscussionsItem\Api\Controller\GetDiscussionReplyUsers::class),
];
