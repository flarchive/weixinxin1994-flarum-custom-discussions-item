# Likes Count 功能实现说明

## 问题描述

Flarum的Discussion模型默认没有`likesCount`属性，这是因为Likes扩展只为主帖（Post）添加了点赞功能，而没有为整个讨论（Discussion）提供点赞总数统计。

## 解决方案

我们通过以下方式为Discussion添加了`likesCount`功能：

### 1. 后端API扩展

**文件**: `src/AddLikesCountToDiscussion.php`

```php
class AddLikesCountToDiscussion
{
    public function __invoke(DiscussionSerializer $serializer, $discussion, array $attributes)
    {
        // 计算讨论中所有帖子的点赞总数
        $likesCount = 0;
        
        // 获取讨论的所有帖子
        $posts = $discussion->posts;
        
        if ($posts) {
            foreach ($posts as $post) {
                // 获取每个帖子的点赞数量
                $postLikesCount = $post->likes()->count();
                $likesCount += $postLikesCount;
            }
        }
        
        $attributes['likesCount'] = $likesCount;
        
        return $attributes;
    }
}
```

### 2. 序列化器注册

**文件**: `extend.php`

```php
(new Extend\ApiSerializer(DiscussionSerializer::class))
    ->attributes(AddLikesCountToDiscussion::class),
```

### 3. 前端模型扩展

**文件**: `js/src/forum/index.js`

```javascript
// 扩展Discussion模型，添加likesCount方法
extend(Discussion.prototype, 'likesCount', function () {
  return this.attribute('likesCount') || 0;
});
```

## 工作原理

1. **后端计算**: 当API返回讨论数据时，`AddLikesCountToDiscussion`类会遍历讨论中的所有帖子，计算每个帖子的点赞数量，并将总数添加到API响应中。

2. **前端访问**: 前端Discussion模型通过`likesCount()`方法可以访问这个属性。

3. **显示逻辑**: 在讨论列表项中，只有当`likesCount > 0`时才显示点赞信息。

## 性能考虑

- 这个实现会在每次API调用时计算点赞总数，对于有大量帖子的讨论可能会有性能影响
- 在生产环境中，建议考虑添加数据库索引或缓存机制来优化性能

## 使用方法

现在您可以在前端代码中安全地使用：

```javascript
const discussion = this.attrs.discussion;
const likesCount = discussion.likesCount(); // 返回讨论的总点赞数
```

## 注意事项

- 确保Likes扩展已安装并启用
- 这个功能依赖于讨论的帖子关系数据
- 如果讨论没有帖子或帖子没有点赞，`likesCount`将返回0
