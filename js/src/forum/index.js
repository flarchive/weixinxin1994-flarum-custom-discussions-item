import app from 'flarum/forum/app';
import { extend } from 'flarum/common/extend';
import DiscussionListItem from 'flarum/forum/components/DiscussionListItem';
import Discussion from 'flarum/common/models/Discussion';
import avatar from 'flarum/common/helpers/avatar';
import icon from 'flarum/common/helpers/icon';
import abbreviateNumber from 'flarum/common/utils/abbreviateNumber';

app.initializers.add('custom/discussions-item', () => {
  console.log('custom/discussions-item');
  
  // 扩展DiscussionListItem组件，添加用户头像、Likes和回帖用户头像列表
  extend(DiscussionListItem.prototype, 'contentItems', function (items) {
    // 在DiscussionListItem的最下边添加增强信息容器
    items.add('enhancedInfo', this.enhancedInfoView(), 5);
    console.log('items', items);
    console.log('this.attrs.discussion', this.attrs);
  });

  // 扩展item-terminalPost，在内部添加作者头像
  extend(DiscussionListItem.prototype, 'infoItems', function (items) {
    // 在item-terminalPost内部添加作者头像
    console.log('items', items);
    items.add('authorAvatar', this.authorAvatarView(), 1);
  });

  // 添加作者头像显示方法
  DiscussionListItem.prototype.authorAvatarView = function () {
    const discussion = this.attrs.discussion;
    
    // 读取后台配置
    const enableAuthorAvatar = app.forum.attribute('custom-discussions-item.enable_author_avatar') !== false;
    const avatarSize = parseInt(app.forum.attribute('custom-discussions-item.avatar_size')) || 24;
    
    // 如果禁用了，不显示
    if (!enableAuthorAvatar) {
      return null;
    }
    
    const author = discussion.user ? discussion.user() : null;
    
    // 如果没有作者信息，不显示
    if (!author) {
      return null;
    }
    
    return (
      <div className="DiscussionListItem-author-avatar">
        {avatar(author, { size: avatarSize })}
      </div>
    );
  };

  // 添加增强信息容器方法（包含likes和reply avatars）
  DiscussionListItem.prototype.enhancedInfoView = function () {
    const discussion = this.attrs.discussion;
    
    // 读取后台配置
    const enableLikesCount = app.forum.attribute('custom-discussions-item.enable_likes_count') !== false;
    const enableReplyAvatars = app.forum.attribute('custom-discussions-item.enable_reply_avatars') !== false;
    
    // 如果都禁用了，不显示
    if (!enableLikesCount && !enableReplyAvatars) {
      return null;
    }
    
    return (
      <div className="DiscussionListItem-enhanced">
        {/* 左侧：点赞数 */}
        <div className="DiscussionListItem-likes">
          {this.likesInfoView()}
        </div>
        
        {/* 右侧：回帖用户头像 */}
        <div className="DiscussionListItem-reply-avatars">
          {this.replyAvatarsView()}
        </div>
      </div>
    );
  };

  // 添加Likes信息显示方法
  DiscussionListItem.prototype.likesInfoView = function () {
    const discussion = this.attrs.discussion;
    
    // 读取后台配置
    const enableLikesCount = app.forum.attribute('custom-discussions-item.enable_likes_count') !== false;
    
    // 如果禁用了，不显示
    if (!enableLikesCount) {
      return null;
    }
    
    // 直接从API响应中获取likesCount
    const likesCount = discussion.data.attributes.likesCount || 0;
    
    return (
      <span className="likes-count">
        {icon('fas fa-heart')}
        <span className="count">
          {app.translator.trans('custom-discussions-item.forum.likes_count', { count: abbreviateNumber(likesCount) })}
        </span>
      </span>
    );
  };

  // 添加回帖用户头像列表显示方法
  DiscussionListItem.prototype.replyAvatarsView = function () {
    const discussion = this.attrs.discussion;
    
    // 读取后台配置
    const enableReplyAvatars = app.forum.attribute('custom-discussions-item.enable_reply_avatars') !== false;
    const maxAvatars = parseInt(app.forum.attribute('custom-discussions-item.max_reply_avatars')) || 5;
    const avatarSize = parseInt(app.forum.attribute('custom-discussions-item.avatar_size')) || 20;
    
    console.log('回帖头像配置读取:', {
      enableReplyAvatars,
      maxAvatars,
      avatarSize
    });
    
    // 如果禁用了，不显示
    if (!enableReplyAvatars) {
      return null;
    }
    
    // 直接从API响应中获取回复用户信息
    const replyUsers = discussion.data.attributes.replyUsers || [];
    
    // 如果没有回帖用户，不显示
    if (replyUsers.length === 0) {
      return null;
    }

    console.log('显示回帖用户头像，数量:', replyUsers.length);

    // 限制显示的头像数量
    const displayUsers = replyUsers.slice(0, maxAvatars);

    return (
      <>
        <span className="reply-avatars-label">
          {app.translator.trans('custom-discussions-item.forum.recent_replies')}:
        </span>
        <div className="avatars-list">
          {displayUsers.map((user, index) => {
            // 创建用户对象以使用Flarum的avatar辅助函数
            const userModel = {
              id: () => user.id,
              displayName: () => user.displayName,
              username: () => user.username,
              avatarUrl: () => user.avatarUrl,
            };
            
            return (
              <span key={index} className="reply-avatar" title={user.displayName}>
                {avatar(userModel, { size: avatarSize })}
              </span>
            );
          })}
          {replyUsers.length > maxAvatars && (
            <span className="more-avatars" title={app.translator.trans('custom-discussions-item.forum.more_replies', { count: replyUsers.length - maxAvatars })}>
              {app.translator.trans('custom-discussions-item.forum.more_replies', { count: replyUsers.length - maxAvatars })}
            </span>
          )}
        </div>
      </>
    );
  };
});