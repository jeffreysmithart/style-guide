/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	Panel,
	PanelBody,
	FormTokenField,
	SelectControl,
} from '@wordpress/components';

import './editor.scss';

const SECTION_OPTIONS = [
	{ label: __( 'Color Palette', 'style-guide' ), value: 'color-palette' },
	{ label: __( 'Font Sizes', 'style-guide' ), value: 'font-sizes' },
	{
		label: __( 'Spacing Scale', 'style-guide' ),
		value: 'spacing-scale',
	},
	{ label: __( 'Shadows', 'style-guide' ), value: 'shadows' },
	{ label: __( 'Gradients', 'style-guide' ), value: 'gradients' },
];

const VALUE_TO_LABEL = Object.fromEntries(
	SECTION_OPTIONS.map( ( o ) => [ o.value, o.label ] )
);
const LABEL_TO_VALUE = Object.fromEntries(
	SECTION_OPTIONS.map( ( o ) => [ o.label.toLowerCase(), o.value ] )
);

export default function Edit( { attributes, setAttributes } ) {
	const { selectedSections, colorFormat } = attributes;
	const blockProps = useBlockProps();

	const selectedLabels = selectedSections
		.map( ( v ) => VALUE_TO_LABEL[ v ] )
		.filter( Boolean );

	const availableSuggestions = SECTION_OPTIONS.filter(
		( o ) => ! selectedSections.includes( o.value )
	).map( ( o ) => o.label );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<Panel>
					<PanelBody
						title={ __( 'Settings', 'style-guide' ) }
					>
						<FormTokenField
							label={ __(
								'Select Sections',
								'style-guide'
							) }
							value={ selectedLabels }
							suggestions={ availableSuggestions }
							onChange={ ( tokens ) => {
								const values = tokens
									.map(
										( t ) =>
											LABEL_TO_VALUE[
												t.toLowerCase()
											]
									)
									.filter( Boolean );
								setAttributes( {
									selectedSections: values,
								} );
							} }
							__experimentalExpandOnFocus
							__experimentalAutoSelectFirstMatch
						/>
						<SelectControl
							__nextHasNoMarginBottom
							label={ __(
								'Default Color Format',
								'style-guide'
							) }
							value={ colorFormat }
							options={ [
								{ label: 'HEX', value: 'hex' },
								{ label: 'RGB', value: 'rgb' },
								{ label: 'HSL', value: 'hsl' },
							] }
							onChange={ ( value ) =>
								setAttributes( { colorFormat: value } )
							}
						/>
					</PanelBody>
				</Panel>
			</InspectorControls>

			<ServerSideRender
				skipBlockSupportAttributes
				block="style-guide-block/style-guide"
				attributes={ attributes }
			/>
		</div>
	);
}
