<?php
$selected_sections = $attributes['selectedSections'] ?? [];
$color_format = $attributes['colorFormat'] ?? 'hex';

?>
<div <?php echo wp_kses_data(get_block_wrapper_attributes()); ?>>
	<?php
	if (!empty($selected_sections)) {
		$theme_settings = wp_get_global_settings();

		foreach ($selected_sections as $section) {
			switch ($section) {
				case 'color-palette':
					$show_default = $theme_settings['color']['defaultPalette'] ?? true;
					$palettes = style_guide_get_preset_groups(
						$theme_settings['color']['palette'] ?? [],
						$show_default
					);

					if (!empty($palettes)) {
						echo '<section class="style-guide-section color-palette">';
						echo '<h2 id="colorPalette">' . esc_html__('Color Palette', 'style-guide') . '</h2>';
						echo '<div class="style-guide-format-toggle-wrapper">';
						echo '<span class="style-guide-format-label">' . esc_html__('Color Format', 'style-guide') . '</span>';
						echo '<div class="style-guide-format-toggle" role="group" aria-label="' . esc_attr__('Color format', 'style-guide') . '">';
						foreach (['hex' => 'HEX', 'rgb' => 'RGB', 'hsl' => 'HSL'] as $fmt => $label) {
							$active = $color_format === $fmt ? ' aria-pressed="true"' : ' aria-pressed="false"';
							echo '<button class="style-guide-format-btn" data-format="' . esc_attr($fmt) . '"' . $active . '>' . esc_html($label) . '</button>';
						}
						echo '</div>';
						echo '</div>';
						foreach ($palettes as $group_name => $colors) {
							echo '<div class="color-palette__group style-group">';
							echo '<h3 class="sub-head" id="colorPalette-' . esc_attr($group_name) . '">' . esc_html(ucwords($group_name)) . '</h3>';
							echo '<ul class="list">';
							foreach ($colors as $color) {
								$display_value = style_guide_convert_color($color['color'], $color_format);
								$css_var = '--wp--preset--color--' . $color['slug'];
								echo '<li>
									<div class="chip chip--color has-' . esc_attr($color['slug']) . '-background-color"></div>
									<details>
										<summary>' . esc_html($color['name']) . '</summary>
										<div class="chip__meta">
											<div class="chip__value">
												<pre class="style-guide-color-value" data-color-hex="' . esc_attr($color['color']) . '">' . esc_html($display_value) . '</pre>
												' . style_guide_copy_button($display_value) . '
											</div>
											<div class="chip__value">
												<pre>' . esc_html($css_var) . '</pre>
												' . style_guide_copy_button($css_var) . '
											</div>
										</div>
									</details>
								</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
						echo '</section>';
					}
					break;

				case 'font-sizes':
					$show_default = $theme_settings['typography']['defaultFontSizes'] ?? true;
					$show_custom = $theme_settings['typography']['customFontSize'] ?? true;
					$font_groups = style_guide_get_preset_groups(
						$theme_settings['typography']['fontSizes'] ?? [],
						$show_default,
						$show_custom
					);

					if (!empty($font_groups)) {
						echo '<section class="style-guide-section font-sizes">';
						echo '<h2 id="fontSizes">' . esc_html__('Font Sizes', 'style-guide') . '</h2>';
						foreach ($font_groups as $group_name => $sizes) {
							echo '<div class="font-sizes__group style-group">';
							echo '<h3 class="sub-head" id="fontSizes-' . esc_attr($group_name) . '">' . esc_html(ucwords($group_name)) . '</h3>';
							echo '<ul class="list list--vertical">';
							foreach ($sizes as $font_size) {
								$fluid = isset($font_size['fluid']) && $font_size['fluid'] ? 'true' : 'false';
								$size = $fluid === 'true' && isset($font_size['fluid']['min'], $font_size['fluid']['max'])
									? "min: {$font_size['fluid']['min']} max: {$font_size['fluid']['max']}"
									: $font_size['size'];
								$css_var = '--wp--preset--font-size--' . $font_size['slug'];
								echo '<li>
									<details>
										<summary class="has-' . esc_attr($font_size['slug']) . '-font-size">' . esc_html($font_size['name']) . ' (' . esc_html($size) . ')</summary>
										<div class="chip__meta">
											<div class="chip__value">
												<pre>' . esc_html__('Size:', 'style-guide') . ' ' . esc_html($size) . '</pre>
											</div>
											<div class="chip__value">
												<pre>' . esc_html__('Fluid:', 'style-guide') . ' ' . esc_html($fluid) . '</pre>
											</div>
											<div class="chip__value">
												<pre>' . esc_html($css_var) . '</pre>
												' . style_guide_copy_button($css_var) . '
											</div>
										</div>
									</details>
								</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
						echo '</section>';
					}
					break;

				case 'spacing-scale':
					$spacing_groups = style_guide_get_preset_groups(
						$theme_settings['spacing']['spacingSizes'] ?? []
					);

					if (!empty($spacing_groups)) {
						echo '<section class="style-guide-section spacing-scale">';
						echo '<h2 id="spacingScale">' . esc_html__('Spacing Scale', 'style-guide') . '</h2>';
						foreach ($spacing_groups as $group_name => $sizes) {
							echo '<div class="spacing-scale__group style-group">';
							echo '<h3 class="sub-head" id="spacingScale-' . esc_attr($group_name) . '">' . esc_html(ucwords($group_name)) . '</h3>';
							echo '<ul class="list list--vertical">';
							foreach ($sizes as $spacing_size) {
								$css_var = '--wp--preset--spacing--' . $spacing_size['slug'];
								echo '<li>
									<div class="chip chip--spacing" style="width:var(' . esc_attr($css_var) . ');"></div>
									<details>
										<summary>' . esc_html($spacing_size['name']) . '</summary>
										<div class="chip__meta">
											<div class="chip__value">
												<pre>' . esc_html__('Size:', 'style-guide') . ' ' . esc_html($spacing_size['size']) . '</pre>
											</div>
											<div class="chip__value">
												<pre>' . esc_html($css_var) . '</pre>
												' . style_guide_copy_button($css_var) . '
											</div>
										</div>
									</details>
								</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
						echo '</section>';
					}
					break;

				case 'shadows':
					$show_default = isset($theme_settings['shadow']['defaultPresets']) ? $theme_settings['shadow']['defaultPresets'] : true;
					$shadow_groups = style_guide_get_preset_groups(
						$theme_settings['shadow']['presets'] ?? [],
						$show_default
					);

					if (!empty($shadow_groups)) {
						echo '<section class="style-guide-section shadows">';
						echo '<h2 id="shadows">' . esc_html__('Shadows', 'style-guide') . '</h2>';
						foreach ($shadow_groups as $group_name => $shadows) {
							echo '<div class="shadows__group style-group">';
							echo '<h3 class="sub-head" id="shadows-' . esc_attr($group_name) . '">' . esc_html(ucwords($group_name)) . '</h3>';
							echo '<ul class="list">';
							foreach ($shadows as $shadow) {
								$css_var = '--wp--preset--shadow--' . $shadow['slug'];
								$shadow_value = 'box-shadow: ' . $shadow['shadow'] . ';';
								echo '<li>
									<div class="chip chip--shadow" style="box-shadow:var(' . esc_attr($css_var) . ');"></div>
									<details>
										<summary>' . esc_html($shadow['name']) . '</summary>
										<div class="chip__meta">
											<div class="chip__value">
												<pre>' . esc_html($shadow_value) . '</pre>
												' . style_guide_copy_button($shadow_value) . '
											</div>
											<div class="chip__value">
												<pre>' . esc_html($css_var) . '</pre>
												' . style_guide_copy_button($css_var) . '
											</div>
										</div>
									</details>
								</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
						echo '</section>';
					}
					break;

				case 'gradients':
					$show_default = $theme_settings['color']['defaultGradients'] ?? true;
					$show_custom = $theme_settings['color']['customGradient'] ?? true;
					$gradient_groups = style_guide_get_preset_groups(
						$theme_settings['color']['gradients'] ?? [],
						$show_default,
						$show_custom
					);

					if (!empty($gradient_groups)) {
						echo '<section class="style-guide-section gradients">';
						echo '<h2 id="gradients">' . esc_html__('Gradients', 'style-guide') . '</h2>';
						foreach ($gradient_groups as $group_name => $gradients) {
							echo '<div class="gradients__group style-group">';
							echo '<h3 class="sub-head" id="gradients-' . esc_attr($group_name) . '">' . esc_html(ucwords($group_name)) . '</h3>';
							echo '<ul class="list">';
							foreach ($gradients as $gradient) {
								$css_var = '--wp--preset--gradient--' . $gradient['slug'];
								$gradient_value = 'background-image: ' . $gradient['gradient'] . ';';
								echo '<li>
									<div class="chip chip--gradient" style="background-image:var(' . esc_attr($css_var) . ');"></div>
									<details>
										<summary>' . esc_html($gradient['name']) . '</summary>
										<div class="chip__meta">
											<div class="chip__value">
												<pre>' . esc_html($gradient_value) . '</pre>
												' . style_guide_copy_button($gradient_value) . '
											</div>
											<div class="chip__value">
												<pre>' . esc_html($css_var) . '</pre>
												' . style_guide_copy_button($css_var) . '
											</div>
										</div>
									</details>
								</li>';
							}
							echo '</ul>';
							echo '</div>';
						}
						echo '</section>';
					}
					break;
			}
		}
	}

	?>
</div>
