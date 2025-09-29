import app from 'flarum/admin/app';

app.initializers.add('custom/discussions-item', () => {
  // 注册扩展设置
  app.extensionData
    .for('custom-discussions-item')
    .registerSetting({
      setting: 'custom-discussions-item.enable_author_avatar',
      label: app.translator.trans('custom-discussions-item.admin.enable_author_avatar'),
      help: app.translator.trans('custom-discussions-item.admin.enable_author_avatar_help'),
      type: 'boolean',
      default: true,
    })
    .registerSetting({
      setting: 'custom-discussions-item.enable_likes_count',
      label: app.translator.trans('custom-discussions-item.admin.enable_likes_count'),
      help: app.translator.trans('custom-discussions-item.admin.enable_likes_count_help'),
      type: 'boolean',
      default: true,
    })
    .registerSetting({
      setting: 'custom-discussions-item.enable_reply_avatars',
      label: app.translator.trans('custom-discussions-item.admin.enable_reply_avatars'),
      help: app.translator.trans('custom-discussions-item.admin.enable_reply_avatars_help'),
      type: 'boolean',
      default: true,
    })
    .registerSetting({
      setting: 'custom-discussions-item.max_reply_avatars',
      label: app.translator.trans('custom-discussions-item.admin.max_reply_avatars'),
      help: app.translator.trans('custom-discussions-item.admin.max_reply_avatars_help'),
      type: 'number',
      default: 5,
      min: 1,
      max: 10,
    })
    .registerSetting({
      setting: 'custom-discussions-item.avatar_size',
      label: app.translator.trans('custom-discussions-item.admin.avatar_size'),
      help: app.translator.trans('custom-discussions-item.admin.avatar_size_help'),
      type: 'number',
      default: 20,
      min: 16,
      max: 48,
    });
});
