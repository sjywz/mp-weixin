# 公众号小程序管理

## 基于laravel+Dcat Admin

### 安装流程

- 安装 `composer install`
- 修改 `.env.example`为`.env` 修改数据库/站点基础配置
- 执行 `php artisan storage:link` 生成资源软链
- 执行 `php artisan key:generate` 生成加密key
- 执行数据库迁移 `php artisan migrate`
- 执行基础数据填充 `php artisan db:seed --class=AdminTablesSeeder`
- 执行 `php artisan admin:create-user` 创建登录账号

- 开启https访问,请修改.env配置`ADMIN_HTTPS=true`

## 完成功能

- 公众号对接开放平台/原始对接
- 关注回复，支持文字/图片/语音回复
- 关键词回复，支持文字/图片/语音，关键词支持完整/模糊/前置/后置匹配
- 潜在回复，延迟消息
- 资源管理
- 用户管理
