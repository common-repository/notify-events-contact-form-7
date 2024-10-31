<?php
/*
Plugin Name: Notify.Events - Contact Form 7
Plugin URI: https://notify.events/en/source/79
Description: Fast and simplest way to integrate Contact Form 7 plugin with more then 30 messengers and platforms including SMS, Voicecall, Facebook messenger, VK, Telegram, Viber, Slack and etc.
Author: Notify.Events
Author URI: https://notify.events/
Version: 1.0.2
License: GPL-2.0
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: notify-events-contact-form-7
Domain Path: /languages/
*/

require_once ABSPATH . 'wp-admin/includes/plugin.php';

use notify_events\modules\contact_form_7\models\ContactForm7;

const WPNE_CF7 = 'notify-events-contact-form-7';

spl_autoload_register(function($class) {
    if (stripos($class, 'notify_events\\modules\\contact_form_7\\') !== 0) {
        return;
    }

    $class_file = __DIR__ . '/' . str_replace(['notify_events\\', '\\'], ['', '/'], $class . '.php');

    if (!file_exists($class_file)) {
        return;
    }

    require_once $class_file;
});

register_activation_hook(__FILE__, function() {
    if (!is_plugin_active('notify-events/notify-events.php') and current_user_can('activate_plugins')) {
        wp_die(__('Sorry, but this plugin requires the <a href="https://ru.wordpress.org/plugins/notify-events/" target="_blank">Notify.Events</a> plugin to be installed and active.<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>', WPNE_CF7), __('Plugin required', WPNE_CF7));
    }

    if (!is_plugin_active('contact-form-7/wp-contact-form-7.php') and current_user_can('activate_plugins')) {
        wp_die(__('Sorry, but this plugin requires the <a href="https://ru.wordpress.org/plugins/contact-form-7/" target="_blank">Contact Form 7</a> plugin to be installed and active.<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>', WPNE_CF7), __('Plugin required', WPNE_CF7));
    }
});

add_action('plugins_loaded', function() {
    if (!is_plugin_active('notify-events/notify-events.php')) {
        deactivate_plugins('notify-events-contact-form-7/notify-events-contact-form-7.php');
        return;
    }

    if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
        deactivate_plugins('notify-events-contact-form-7/notify-events-contact-form-7.php');
        return;
    }
});

add_action('wpne_module_init', function() {
    ContactForm7::register();
});
