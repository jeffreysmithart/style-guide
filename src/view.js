/**
 * Frontend script for the Style Guide block.
 * Handles copy-to-clipboard and color format toggle functionality.
 */

const COPY_ICON =
	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';

const CHECK_ICON =
	'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';

/**
 * Copy to clipboard.
 */
document
	.querySelectorAll(
		'.wp-block-style-guide-block-style-guide .style-guide-copy'
	)
	.forEach( ( button ) => {
		button.addEventListener( 'click', async () => {
			const text = button.dataset.copy;

			try {
				await navigator.clipboard.writeText( text );
			} catch {
				return;
			}

			button.innerHTML = CHECK_ICON;
			button.classList.add( 'copied' );

			setTimeout( () => {
				button.innerHTML = COPY_ICON;
				button.classList.remove( 'copied' );
			}, 2000 );
		} );
	} );

/**
 * Color format conversion utilities.
 */
function hexToRgb( hex ) {
	hex = hex.replace( '#', '' );
	if ( hex.length === 3 ) {
		hex = hex[ 0 ] + hex[ 0 ] + hex[ 1 ] + hex[ 1 ] + hex[ 2 ] + hex[ 2 ];
	}
	if ( hex.length !== 6 ) {
		return null;
	}
	const r = parseInt( hex.substring( 0, 2 ), 16 );
	const g = parseInt( hex.substring( 2, 4 ), 16 );
	const b = parseInt( hex.substring( 4, 6 ), 16 );
	return `rgb(${ r }, ${ g }, ${ b })`;
}

function hexToHsl( hex ) {
	hex = hex.replace( '#', '' );
	if ( hex.length === 3 ) {
		hex = hex[ 0 ] + hex[ 0 ] + hex[ 1 ] + hex[ 1 ] + hex[ 2 ] + hex[ 2 ];
	}
	if ( hex.length !== 6 ) {
		return null;
	}
	let r = parseInt( hex.substring( 0, 2 ), 16 ) / 255;
	let g = parseInt( hex.substring( 2, 4 ), 16 ) / 255;
	let b = parseInt( hex.substring( 4, 6 ), 16 ) / 255;

	const max = Math.max( r, g, b );
	const min = Math.min( r, g, b );
	let h;
	let s;
	const l = ( max + min ) / 2;

	if ( max === min ) {
		h = 0;
		s = 0;
	} else {
		const d = max - min;
		s = l > 0.5 ? d / ( 2 - max - min ) : d / ( max + min );
		switch ( max ) {
			case r:
				h = ( ( g - b ) / d + ( g < b ? 6 : 0 ) ) / 6;
				break;
			case g:
				h = ( ( b - r ) / d + 2 ) / 6;
				break;
			case b:
				h = ( ( r - g ) / d + 4 ) / 6;
				break;
		}
	}

	return `hsl(${ Math.round( h * 360 ) }, ${ Math.round(
		s * 100
	) }%, ${ Math.round( l * 100 ) }%)`;
}

function convertColor( hex, format ) {
	if ( format === 'hex' ) {
		return hex;
	}
	if ( format === 'rgb' ) {
		return hexToRgb( hex ) || hex;
	}
	if ( format === 'hsl' ) {
		return hexToHsl( hex ) || hex;
	}
	return hex;
}

/**
 * Color format toggle.
 */
document
	.querySelectorAll(
		'.wp-block-style-guide-block-style-guide .style-guide-format-toggle'
	)
	.forEach( ( toggle ) => {
		const section = toggle.closest( '.color-palette' );
		const colorValues = section.querySelectorAll(
			'.style-guide-color-value'
		);

		toggle.querySelectorAll( '.style-guide-format-btn' ).forEach(
			( btn ) => {
				btn.addEventListener( 'click', () => {
					const format = btn.dataset.format;

					// Update active button state
					toggle
						.querySelectorAll( '.style-guide-format-btn' )
						.forEach( ( b ) =>
							b.setAttribute( 'aria-pressed', 'false' )
						);
					btn.setAttribute( 'aria-pressed', 'true' );

					// Convert all color values in this section
					colorValues.forEach( ( pre ) => {
						const hex = pre.dataset.colorHex;
						const converted = convertColor( hex, format );
						pre.textContent = converted;

						// Update the adjacent copy button's data-copy value
						const copyBtn =
							pre.parentElement.querySelector(
								'.style-guide-copy'
							);
						if ( copyBtn ) {
							copyBtn.dataset.copy = converted;
						}
					} );
				} );
			}
		);
	} );
