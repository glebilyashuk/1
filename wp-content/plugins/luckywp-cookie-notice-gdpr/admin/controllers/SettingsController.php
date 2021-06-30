<?php

namespace luckywp\cookieNoticeGdpr\admin\controllers;

use luckywp\cookieNoticeGdpr\core\admin\AdminController;
use WP_Plugin_Install_List_Table;

class SettingsController extends AdminController
{

    public function actionIndex()
    {
        $this->render('index');
    }

    public function plugins()
    {
        require_once ABSPATH . 'wp-admin/includes/class-wp-plugin-install-list-table.php';

        add_filter('install_plugins_nonmenu_tabs', function ($tabs) {
            $tabs[] = 'luckywp';
            return $tabs;
        });
        add_filter('install_plugins_table_api_args_luckywp', function ($args) {
            global $paged;
            return [
                'page' => $paged,
                'per_page' => 12,
                'locale' => get_user_locale(),
                'search' => 'LuckyWP',
            ];
        });

        $_POST['tab'] = 'luckywp';
        $table = new WP_Plugin_Install_List_Table();
        $table->prepare_items();

        wp_enqueue_script('plugin-install');
        add_thickbox();
        wp_enqueue_script('updates');

        $this->render('plugins', [
            'table' => $table,
        ]);
    }
}
