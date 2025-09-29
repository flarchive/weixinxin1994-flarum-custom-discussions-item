# 发布指南

## 发布到Packagist的步骤

### 1. 准备GitHub仓库

1. 在GitHub上创建新仓库：`weixinxin1994/flarum-custom-discussions-item`
2. 将本地代码推送到GitHub：

```bash
# 初始化Git仓库（如果还没有）
git init

# 添加远程仓库
git remote add origin https://github.com/Weixinxin1994/flarum-custom-discussions-item.git

# 添加所有文件
git add .

# 提交更改
git commit -m "Initial release v1.0.0"

# 推送到GitHub
git push -u origin main
```

### 2. 创建Git标签

```bash
# 创建版本标签
git tag -a v1.0.0 -m "Release version 1.0.0"

# 推送标签到GitHub
git push origin v1.0.0
```

### 3. 注册Packagist账户

1. 访问 [Packagist.org](https://packagist.org/)
2. 使用GitHub账户登录
3. 点击 "Submit" 按钮
4. 输入仓库URL：`https://github.com/Weixinxin1994/flarum-custom-discussions-item`

### 4. 配置自动更新

1. 在Packagist上找到你的包
2. 点击 "Settings" 标签
3. 启用 "Auto-Update" 功能
4. 添加GitHub Webhook（如果需要）

### 5. 验证发布

```bash
# 测试安装
composer require weixinxin1994/flarum-custom-discussions-item

# 检查包信息
composer show weixinxin1994/flarum-custom-discussions-item
```

## 更新版本

### 1. 更新版本号

```bash
# 更新composer.json中的版本
# 更新package.json中的版本
# 更新README.md中的版本信息
```

### 2. 创建新标签

```bash
git add .
git commit -m "Release version 1.0.1"
git tag -a v1.0.1 -m "Release version 1.0.1"
git push origin main
git push origin v1.0.1
```

## 发布检查清单

- [ ] composer.json 配置正确
- [ ] README.md 完整且准确
- [ ] LICENSE 文件存在
- [ ] .gitignore 配置正确
- [ ] 代码测试通过
- [ ] 版本号更新
- [ ] Git标签创建
- [ ] Packagist包创建
- [ ] 自动更新配置

## 常见问题

### Q: 包名已存在怎么办？
A: 修改composer.json中的name字段，使用更独特的命名。

### Q: 如何更新已发布的包？
A: 创建新的Git标签，Packagist会自动检测并更新。

### Q: 如何删除错误的发布？
A: 在Packagist上可以删除包，但建议使用新版本号重新发布。

## 推广你的扩展

1. 在Flarum社区论坛发布
2. 在相关QQ群/微信群分享
3. 写博客文章介绍
4. 制作演示视频
5. 收集用户反馈并持续改进
