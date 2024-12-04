<?php
$selected_sections = $attributes['selectedSections'] ?? [];

?>
<div <?php echo wp_kses_data(get_block_wrapper_attributes()); ?>>
	<?php
	if (!empty($selected_sections)) {
		// Retrieve theme settings from theme.json
		$theme_settings = wp_get_global_settings();

		foreach ($selected_sections as $section) {
			switch ($section) {
				case 'color-palette':
					// Extract the color palette
					$color_palettes = isset($theme_settings['color']['palette']) ? $theme_settings['color']['palette'] : [];
					$show_default_palette = isset($theme_settings['color']['defaultPalette']) ? $theme_settings['color']['defaultPalette'] : true;
					// if $show_default_palette is true, show the default palette otherwise, remove the default palette from the array
					if ($show_default_palette === false) {
						unset($color_palettes['default']);
					}

					// Display color palette
					if (!empty($color_palettes)) {
						$array_keys = array_keys($color_palettes);
						$palette_count = 0;
						echo '<section class="style-guide-section color-palette">';
						echo '<h2 id="colorPalette">Color Palette</h2>';

						foreach ($color_palettes as $palette_name => $color_palette) {
							echo '<div class="color-palette__group style-group">';
							// output array key as the name of the color palette
							echo '<h3 class="sub-head" id="colorPalette-' . esc_html($array_keys[$palette_count]) . '">' . esc_html(ucwords($array_keys[$palette_count])) . '</h3>';

							echo '<ul class="list">';
							foreach ($color_palette as $color) {
								echo '<li>
								<div class="chip chip--color has-' . esc_html($color['slug']) . '-background-color"></div>
								<details>
									<summary>' . esc_html($color['name']) . '</summary>
									<div class="chip__meta">					
										<p>' . esc_html($color['color']) . '</p>
										<p>--wp--preset--color--' . esc_html($color['slug']) . '</p>
									</div>
								</details>
							</li>';
							}
							echo '</ul>';
							echo '</div>';
							$palette_count++;
						}
						echo '</section>';
					}
					break;
				case 'font-sizes':
					// Extract the font sizes
					$default_font_sizes = isset($theme_settings['typography']['fontSizes']['default']) && $theme_settings['typography']['defaultFontSizes'] === true ? array('default' => $theme_settings['typography']['fontSizes']['default']) : [];
					$custom_font_sizes = isset($theme_settings['typography']['fontSizes']['custom']) && $theme_settings['typography']['customFontSize'] ? array('custom' => $theme_settings['typography']['fontSizes']['custom']) : [];
					$theme_font_sizes = isset($theme_settings['typography']['fontSizes']['theme']) ? array('theme' => $theme_settings['typography']['fontSizes']['theme'])  : [];
					$font_sizes_array = array_merge($default_font_sizes, $theme_font_sizes, $custom_font_sizes);

					// Display font sizes
					if (!empty($font_sizes_array)) {
						$array_keys = array_keys($font_sizes_array);
						$font_size_count = 0;
						echo '<section class="style-guide-section font-sizes">';
						echo '<h2 id="fontSizes">Font Sizes</h2>';
						foreach ($font_sizes_array as $font_sizes) {
							echo '<div class="font-sizes__group style-group">';
							// output array key as the name of the font size
							echo '<h3 class="sub-head" id="fontSizes-' . esc_html($array_keys[$font_size_count]) . '">' . esc_html(ucwords($array_keys[$font_size_count])) . '</h3>';
							echo '<ul class="list list--vertical">';
							foreach ($font_sizes as $font_size) {

								$fluid = isset($font_size['fluid']) && $font_size['fluid'] ? 'true' : 'false';
								$size = $fluid === 'true' && isset($font_size['fluid']['min'], $font_size['fluid']['max'])
									? "min: {$font_size['fluid']['min']} max: {$font_size['fluid']['max']}"
									: $font_size['size'];
								echo '<li>
										<details>
											<summary class="has-' . esc_attr($font_size['slug']) . '-font-size">' . esc_html($font_size['name']) . ' (' . esc_html($size) . ')</summary>
											<div class="chip__meta">
												<p>Size: ' . esc_html($size) . '</p>
												<p>Fluid: ' . esc_html($fluid) . '</p>
												<p>--wp--preset--font-size--' . esc_html($font_size['slug']) . '</p>
											</div>
										</details>
								</li>';
							}
							echo '</ul>';
							echo '</div>';
							$font_size_count++;
						}
						echo '</section>';
					}
					break;


				case 'spacing-scale':
					// Extract the spacing (e.g., padding, margin scale)
					$spacing_sizes = isset($theme_settings['spacing']['spacingSizes']['theme']) ? $theme_settings['spacing']['spacingSizes']['theme'] : [];

					// Display spacing
					if (!empty($spacing_sizes)) {
						echo '<section class="style-guide-section spacing-scale">';
						echo '<h2 id="spacingScale">Spacing Scale</h2>';
						echo '<ul class="list list--vertical">';
						foreach ($spacing_sizes as $spacing_size) {
							echo '<li>
									<div class="chip chip--spacing" style="width:var(--wp--preset--spacing--' . esc_attr($spacing_size['slug']) . ');"></div>
									<details>
										<summary>' . esc_html($spacing_size['name']) . '</summary>
										<div class="chip__meta">
											<p>Size: ' . esc_html($spacing_size['size']) . '</p>
											<p>--wp--preset--spacing--' . esc_html($spacing_size['slug']) . '</p>
										</div>
									</details>
								</li>';
						}
						echo '</ul>';
						echo '</section>';
					}
					break;
				case 'shadows':

					// Extract the shadows
					$default_shadows = $theme_settings['shadow']['defaultPresets'] ? array('default' => $theme_settings['shadow']['presets']['default']) : [];
					$custom_shadows = isset($theme_settings['shadow']['presets']['custom']) ? array('custom' => $theme_settings['shadow']['presets']['custom']) : [];
					$theme_shadows = isset($theme_settings['shadow']['presets']['theme']) ? array('theme' => $theme_settings['shadow']['presets']['theme']) : [];
					$shadows_array = array_merge($default_shadows, $theme_shadows, $custom_shadows);

					if (!empty($shadows_array)) {
						$array_keys = array_keys($shadows_array);
						$count = 0;
						echo '<section class="style-guide-section shadows">';
						echo '<h2 id="shadows">Shadows</h2>';
						foreach ($shadows_array as $shadows) {
							echo '<div class="shadows__group style-group">';
							// output array key as the name of the shadow
							echo '<h3 class="sub-head" id="shadows-' . esc_html($array_keys[$count]) . '">' . esc_html(ucwords($array_keys[$count])) . '</h3>';
							echo '<ul class="list">';
							foreach ($shadows as $shadow) {
								echo '<li>
										<div class="chip chip--shadow" style="box-shadow:var(--wp--preset--shadow--' . esc_attr($shadow['slug']) . ');"></div>
										<details>
											<summary>' . esc_html($shadow['name']) . '</summary>
											<div class="chip__meta">					
												<p>box-shadow:' . esc_html($shadow['shadow']) . ';</p>
												<p>--wp--preset--shadow--' . esc_html($shadow['slug']) . '</p>
											</div>
										</details>
									</li>';
							}
							echo '</ul>';
							echo '</div>';
							$count++;
						}
						echo '</section>';
					}
					break;
				case 'gradients';


					// Extract the gradients
					$defatult_gradients = isset($theme_settings['color']['gradients']['default']) && $theme_settings['color']['defaultGradients'] === true ? array('default' => $theme_settings['color']['gradients']['default']) : [];
					$custom_gradients = isset($theme_settings['color']['gradients']['custom']) && $theme_settings['color']['customGradient'] === true ? array('custom' => $theme_settings['color']['gradients']['custom'])  : [];
					$theme_gradients = isset($theme_settings['color']['gradients']['theme']) ? array('theme' => $theme_settings['color']['gradients']['theme']) : [];
					$gradients_array = array_merge($defatult_gradients, $theme_gradients,  $custom_gradients);

					if (!empty($gradients_array)) {
						$array_keys = array_keys($gradients_array);
						$count = 0;
						echo '<section class="style-guide-section gradients">';
						echo '<h2 id="gradients">Gradients</h2>';
						foreach ($gradients_array as $gradients) {
							echo '<div class="gradients__group style-group">';
							// output array key as the name of the gradient
							echo '<h3 class="sub-head" id="gradients-' . esc_html($array_keys[$count]) . '">' . esc_html(ucwords($array_keys[$count])) . '</h3>';
							echo '<ul class="list">';
							foreach ($gradients as $gradient) {
								echo '<li>
												<div class="chip chip--gradient" style="background-image:var(--wp--preset--gradient--' . esc_attr($gradient['slug']) . ');"></div>
												<details>
													<summary>' . esc_html($gradient['name']) . '</summary>
													<div class="chip__meta">					
														<p>background-image:' . esc_html($gradient['gradient']) . ';</p>
														<p>--wp--preset--gradient--' . esc_html($gradient['slug']) . '</p>
													</div>
												</details>
											</li>';
							}
							echo '</ul>';
							echo '</div>';
							$count++;
						}
						echo '</section>';
					}

					break;
			}
		}
	}

	?>
</div>