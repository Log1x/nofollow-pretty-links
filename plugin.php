<?php

/**
 * Plugin Name: Nofollow Pretty Links
 * Plugin URI:  https://github.com/log1x/nofollow-pretty-links
 * Description: Add `rel="nofollow"` and `target="_blank"` to URL's created by Pretty Links
 * Version:     1.0.3
 * Author:      Brandon Nifong
 * Author URI:  https://github.com/log1x
 * Licence:     MIT
 */

namespace Log1x\Plugin\NofollowPrettyLinks;

add_action('init', new class
{
    /**
     * Invoke the plugin.
     *
     * @return void
     */
    public function __invoke()
    {
        require_once file_exists($composer = __DIR__.'/vendor/autoload.php') ?
            $composer :
            __DIR__.'/dist/autoload.php';

        return new NofollowPrettyLinks(
            plugin_dir_path(__FILE__),
            plugin_dir_url(__FILE__)
        );
    }
});
