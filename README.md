# Flarum Custom Discussions Item

Enhanced Discussions List Item for Flarum - Add author avatars, likes count, and reply user avatars to discussion list items.

## 功能特性

- ✨ **作者头像显示** - 在讨论列表中显示讨论作者的头像
- ❤️ **点赞数统计** - 显示讨论的总点赞数
- 👥 **回帖用户头像** - 显示最近回帖用户的头像列表
- ⚙️ **后台配置** - 可控制各项功能的开启/关闭
- 📱 **响应式设计** - 支持移动端显示
- 🌍 **多语言支持** - 支持中文和英文

## 安装

```bash
composer require weixinxin1994/flarum-custom-discussions-item
```

## 启用

```bash
php flarum extension:enable custom-discussions-item
```

## 配置

在Flarum后台的扩展设置中，你可以配置：

- **显示作者头像** - 控制是否显示讨论作者头像
- **显示点赞数** - 控制是否显示点赞统计
- **显示回帖用户头像** - 控制是否显示回帖用户头像
- **最大回帖头像数量** - 设置显示的回帖用户头像数量（1-10个）
- **头像大小** - 设置头像的显示大小（像素）

## 截图

![讨论列表增强效果](https://via.placeholder.com/800x400?text=Discussion+List+Enhancement)

## 技术实现

- 扩展了 `DiscussionSerializer` 来添加 `likesCount` 和 `replyUsers` 属性
- 扩展了 `ForumSerializer` 来传递后台配置到前端
- 使用 `DiscussionListItem` 组件扩展来添加新的显示元素
- 支持Flarum的权限系统和缓存机制

## 开发

### 本地开发

1. 克隆仓库
```bash
git clone https://github.com/Weixinxin1994/flarum-custom-discussions-item.git
cd flarum-custom-discussions-item
```

2. 安装依赖
```bash
composer install
```

3. 编译资源
```bash
npm install
npm run build
```

### 测试

```bash
composer test
```

## 更新日志

### v1.0.0
- 初始版本发布
- 支持作者头像、点赞数、回帖用户头像显示
- 完整的后台配置界面
- 中英文语言支持

## 许可证

MIT License. 详见 [LICENSE](LICENSE) 文件。

## 支持

如果你遇到问题或有建议，请：

1. 查看 [Issues](https://github.com/Weixinxin1994/flarum-custom-discussions-item/issues)
2. 创建新的 Issue
3. 联系开发者：903386832@qq.com

## 贡献

欢迎提交 Pull Request 来改进这个扩展！

## 相关链接

- [Flarum官网](https://flarum.org/)
- [Flarum扩展开发文档](https://docs.flarum.org/extend/)
- [Packagist页面](https://packagist.org/packages/weixinxin1994/flarum-custom-discussions-item)