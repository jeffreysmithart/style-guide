<?php

/**
 * Plugin Name:       Style Guide
 * Description:       A simple block for displaying your theme.json styles.
 * Requires at least: 6.6
 * Requires PHP:      8.0
 * Version:           1.3.0
 * Author:            Jeffrey Smith
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       style-guide
 *
 * @package StyleGuide
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

/**
 * Merge default, theme, and custom preset groups from theme.json settings.
 *
 * @param array $presets      The preset array containing default/theme/custom keys.
 * @param bool  $show_default Whether to include the default group.
 * @param bool  $show_custom  Whether to include the custom group.
 * @return array Merged groups keyed by origin name.
 */
function style_guide_get_preset_groups($presets, $show_default = true, $show_custom = true)
{
	$groups = [];
	if ($show_default && isset($presets['default'])) {
		$groups['default'] = $presets['default'];
	}
	if (isset($presets['theme'])) {
		$groups['theme'] = $presets['theme'];
	}
	if ($show_custom && isset($presets['custom'])) {
		$groups['custom'] = $presets['custom'];
	}
	return $groups;
}

/**
 * Convert a hex color value to the specified format.
 *
 * @param string $color  The color value (expected hex format).
 * @param string $format The target format: 'hex', 'rgb', or 'hsl'.
 * @return string The converted color string.
 */
function style_guide_convert_color($color, $format = 'hex')
{
	if ($format === 'hex' || empty($color)) {
		return $color;
	}

	$hex = ltrim($color, '#');
	if (strlen($hex) === 3) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
		return $color;
	}

	$r = hexdec(substr($hex, 0, 2));
	$g = hexdec(substr($hex, 2, 2));
	$b = hexdec(substr($hex, 4, 2));

	if ($format === 'rgb') {
		return "rgb({$r}, {$g}, {$b})";
	}

	if ($format === 'hsl') {
		$r /= 255;
		$g /= 255;
		$b /= 255;
		$max = max($r, $g, $b);
		$min = min($r, $g, $b);
		$l = ($max + $min) / 2;

		if ($max === $min) {
			$h = 0;
			$s = 0;
		} else {
			$d = $max - $min;
			$s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
			switch ($max) {
				case $r:
					$h = (($g - $b) / $d + ($g < $b ? 6 : 0)) / 6;
					break;
				case $g:
					$h = (($b - $r) / $d + 2) / 6;
					break;
				case $b:
					$h = (($r - $g) / $d + 4) / 6;
					break;
			}
		}

		$h = round($h * 360);
		$s = round($s * 100);
		$l = round($l * 100);
		return "hsl({$h}, {$s}%, {$l}%)";
	}

	return $color;
}

/**
 * Render a copy-to-clipboard button.
 *
 * @param string $value The value to copy when clicked.
 * @return string Button HTML.
 */
function style_guide_copy_button($value)
{
	$icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
	return '<button class="style-guide-copy" data-copy="' . esc_attr($value) . '" aria-label="' . esc_attr__('Copy to clipboard', 'style-guide') . '">' . $icon . '</button>';
}
