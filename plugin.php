<?php

/**
 * Plugin Name: Nofollow Pretty Links
 * Plugin URI:  https://github.com/log1x/nofollow-pretty-links
 * Description: Add `rel="nofollow"` and `target="_blank"` to URL's created by Pretty Links
 * Version:     1.0.6
 * Author:      Brandon Nifong
 * Author URI:  https://github.com/log1x
 * Licence:     MIT
 */

namespace Log1x\Plugin\NofollowPrettyLinks;

if (file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    require_once $composer;
}

add_action('init', fn () => (new NofollowPrettyLinks(
    plugin_dir_path(__FILE__),
    plugin_dir_url(__FILE__)
))->boot());
