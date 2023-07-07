<?php

namespace Database\Seeders;

use Dcat\Admin\Models;
use Illuminate\Database\Seeder;
use DB;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // base tables
        Models\Menu::truncate();
        Models\Menu::insert(
            [
                [
                    "id" => 1,
                    "parent_id" => 0,
                    "order" => 1,
                    "title" => "Index",
                    "icon" => "feather icon-bar-chart-2",
                    "uri" => "/",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => NULL
                ],
                [
                    "id" => 2,
                    "parent_id" => 0,
                    "order" => 12,
                    "title" => "Admin",
                    "icon" => "feather icon-settings",
                    "uri" => "",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => "2023-06-19 21:15:10"
                ],
                [
                    "id" => 3,
                    "parent_id" => 2,
                    "order" => 13,
                    "title" => "Users",
                    "icon" => "",
                    "uri" => "auth/users",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => "2023-06-19 21:15:10"
                ],
                [
                    "id" => 4,
                    "parent_id" => 2,
                    "order" => 14,
                    "title" => "Roles",
                    "icon" => "",
                    "uri" => "auth/roles",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => "2023-06-19 21:15:10"
                ],
                [
                    "id" => 5,
                    "parent_id" => 2,
                    "order" => 15,
                    "title" => "Permission",
                    "icon" => "",
                    "uri" => "auth/permissions",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => "2023-06-19 21:15:10"
                ],
                [
                    "id" => 6,
                    "parent_id" => 2,
                    "order" => 16,
                    "title" => "Menu",
                    "icon" => "",
                    "uri" => "auth/menu",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => "2023-06-19 21:15:10"
                ],
                [
                    "id" => 7,
                    "parent_id" => 2,
                    "order" => 17,
                    "title" => "Extensions",
                    "icon" => "",
                    "uri" => "auth/extensions",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => "2023-06-19 21:15:10"
                ],
                [
                    "id" => 8,
                    "parent_id" => 0,
                    "order" => 2,
                    "title" => "公众号/小程序",
                    "icon" => "fa-ge",
                    "uri" => "mp",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 07:10:36",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 9,
                    "parent_id" => 0,
                    "order" => 7,
                    "title" => "平台",
                    "icon" => "fa-adjust",
                    "uri" => "platform",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 07:10:54",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 10,
                    "parent_id" => 14,
                    "order" => 5,
                    "title" => "消息",
                    "icon" => "fa-dropbox",
                    "uri" => "mpmessage",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 19:32:09",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 11,
                    "parent_id" => 0,
                    "order" => 8,
                    "title" => "平台通知",
                    "icon" => "fa-adjust",
                    "uri" => "platevent",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-03-30 19:32:32",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 12,
                    "parent_id" => 0,
                    "order" => 11,
                    "title" => "公众号菜单",
                    "icon" => "fa-align-justify",
                    "uri" => "/mpmenu",
                    "extension" => "",
                    "show" => 0,
                    "created_at" => "2023-05-07 11:09:57",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 13,
                    "parent_id" => 0,
                    "order" => 10,
                    "title" => "素材管理",
                    "icon" => NULL,
                    "uri" => "/material",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-05-07 11:31:08",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 14,
                    "parent_id" => 0,
                    "order" => 3,
                    "title" => "自动回复",
                    "icon" => "fa-adn",
                    "uri" => NULL,
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-06-18 09:50:06",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 16,
                    "parent_id" => 14,
                    "order" => 4,
                    "title" => "回复规则",
                    "icon" => "fa-adjust",
                    "uri" => "mp_auto",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-06-18 09:52:25",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 17,
                    "parent_id" => 0,
                    "order" => 9,
                    "title" => "资源管理",
                    "icon" => "fa-diamond",
                    "uri" => "resource",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-06-19 21:15:01",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 18,
                    "parent_id" => 0,
                    "order" => 6,
                    "title" => "微信用户",
                    "icon" => "fa-address-book-o",
                    "uri" => "mp_user",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-06-24 11:54:15",
                    "updated_at" => "2023-06-26 21:34:31"
                ],
                [
                    "id" => 19,
                    "parent_id" => 14,
                    "order" => 18,
                    "title" => "潜在回复",
                    "icon" => "fa-anchor",
                    "uri" => "delaymsg",
                    "extension" => "",
                    "show" => 1,
                    "created_at" => "2023-07-03 21:17:57",
                    "updated_at" => "2023-07-03 21:17:57"
                ]
            ]
        );

        Models\Permission::truncate();
        Models\Permission::insert(
            [
                [
                    "id" => 1,
                    "name" => "Auth management",
                    "slug" => "auth-management",
                    "http_method" => "",
                    "http_path" => "",
                    "order" => 1,
                    "parent_id" => 0,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => NULL
                ],
                [
                    "id" => 2,
                    "name" => "Users",
                    "slug" => "users",
                    "http_method" => "",
                    "http_path" => "/auth/users*",
                    "order" => 2,
                    "parent_id" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => NULL
                ],
                [
                    "id" => 3,
                    "name" => "Roles",
                    "slug" => "roles",
                    "http_method" => "",
                    "http_path" => "/auth/roles*",
                    "order" => 3,
                    "parent_id" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => NULL
                ],
                [
                    "id" => 4,
                    "name" => "Permissions",
                    "slug" => "permissions",
                    "http_method" => "",
                    "http_path" => "/auth/permissions*",
                    "order" => 4,
                    "parent_id" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => NULL
                ],
                [
                    "id" => 5,
                    "name" => "Menu",
                    "slug" => "menu",
                    "http_method" => "",
                    "http_path" => "/auth/menu*",
                    "order" => 5,
                    "parent_id" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => NULL
                ],
                [
                    "id" => 6,
                    "name" => "Extension",
                    "slug" => "extension",
                    "http_method" => "",
                    "http_path" => "/auth/extensions*",
                    "order" => 6,
                    "parent_id" => 1,
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => NULL
                ]
            ]
        );

        Models\Role::truncate();
        Models\Role::insert(
            [
                [
                    "id" => 1,
                    "name" => "Administrator",
                    "slug" => "administrator",
                    "created_at" => "2023-03-30 06:54:51",
                    "updated_at" => "2023-03-30 06:54:51"
                ],
                [
                    "id" => 2,
                    "name" => "test",
                    "slug" => "test",
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ]
            ]
        );

        Models\Setting::truncate();
		Models\Setting::insert(
			[

            ]
		);

		Models\Extension::truncate();
		Models\Extension::insert(
			[

            ]
		);

		Models\ExtensionHistory::truncate();
		Models\ExtensionHistory::insert(
			[

            ]
		);

        // pivot tables
        DB::table('admin_permission_menu')->truncate();
		DB::table('admin_permission_menu')->insert(
			[

            ]
		);

        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_menu')->insert(
            [
                [
                    "role_id" => 1,
                    "menu_id" => 14,
                    "created_at" => "2023-06-18 09:50:06",
                    "updated_at" => "2023-06-18 09:50:06"
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 16,
                    "created_at" => "2023-06-18 09:52:25",
                    "updated_at" => "2023-06-18 09:52:25"
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 17,
                    "created_at" => "2023-06-19 21:15:01",
                    "updated_at" => "2023-06-19 21:15:01"
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 18,
                    "created_at" => "2023-06-24 11:54:15",
                    "updated_at" => "2023-06-24 11:54:15"
                ],
                [
                    "role_id" => 1,
                    "menu_id" => 19,
                    "created_at" => "2023-07-03 21:17:57",
                    "updated_at" => "2023-07-03 21:17:57"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 1,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 2,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 3,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 4,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 5,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 6,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 7,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 8,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 9,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 10,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 11,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 12,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 13,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 14,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "menu_id" => 16,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ]
            ]
        );

        DB::table('admin_role_permissions')->truncate();
        DB::table('admin_role_permissions')->insert(
            [
                [
                    "role_id" => 2,
                    "permission_id" => 2,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 3,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 4,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 5,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ],
                [
                    "role_id" => 2,
                    "permission_id" => 6,
                    "created_at" => "2023-06-18 20:33:42",
                    "updated_at" => "2023-06-18 20:33:42"
                ]
            ]
        );

        // finish
    }
}
