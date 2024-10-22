<?php

/**
 * Plugin Name:       Style Guide
 * Description:       A simple block for displaying your theme.json styles..
 * Requires at least: 6.6
 * Requires PHP:      8.0
 * Version:           1.1.2
 * Author:            Jeffrey Smith
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       style-guide
 *
 * @package CreateBlock
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Block Initializer.
 */
function style_guide_block_init()
{
	register_block_type(__DIR__ . '/build');
}
add_action('init', 'style_guide_block_init');
