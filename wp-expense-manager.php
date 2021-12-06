<?php

/**
 * Plugin Name: WP Expense Manager
 * Plugin URI: https://example.com/plugins/the-basics/
 * Description: A WP plugin for accounting your personal/business expenses
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Mak Alamin
 * Author URI:
 * License: GPL v2 or later
 * License URI: https: //www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://github.com/mak-alamin/wp-expense-manager.git
 * Text Domain: wp-expense-manager
 * Domain Path: /languages
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Autoload necessary files.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class.
 */
final class WP_Expense_Manager
{
    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0';

    /**
     * Class constructor
     */
    private function __construct()
    {
        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('plugins_loaded', [$this, 'init_plugin_classes']);
    }

    /**
     * Initializes a single instance of this class
     *
     * @return \WP_Expense_Manager
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define necessary constants
     *
     * @return void
     */
    public function define_constants()
    {
        define('WP_EM_VERSION', self::version);
        define('WP_EM_TXT_DOMAIN', 'wp-expense-manager');
        define('WP_EM_FILE', __FILE__);
        define('WP_EM_PATH', __DIR__);
        define('WP_EM_URL', plugins_url('', WP_EM_FILE));
        define('WP_EM_ASSETS', WP_EM_URL . '/assets');
    }

    /**
     * Initializes the required plugin classes
     *
     * @return void
     */
    public function init_plugin_classes()
    {
        if (is_admin()) {
            new ExpenseManager\Admin();
        }
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate()
    {
        $installer = new ExpenseManager\Installer();
        $installer->install();
    }
}

/**
 * Initializes the main plugin
 *
 * @return \WP_Expense_Manager
 */
WP_Expense_Manager::init();
