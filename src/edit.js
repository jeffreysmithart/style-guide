/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	Panel,
	PanelBody,
	CheckboxControl,
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

export default function Edit( { attributes, setAttributes } ) {
	const { selectedSections, colorFormat } = attributes;
	const blockProps = useBlockProps();

	const handleSectionToggle = ( value, checked ) => {
		if ( checked ) {
			setAttributes( {
				selectedSections: [ ...selectedSections, value ],
			} );
		} else {
			setAttributes( {
				selectedSections: selectedSections.filter(
					( s ) => s !== value
				),
			} );
		}
	};

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Sections', 'style-guide' ) }>
						{ SECTION_OPTIONS.map( ( option ) => (
							<CheckboxControl
								key={ option.value }
								__nextHasNoMarginBottom
								label={ option.label }
								checked={ selectedSections.includes(
									option.value
								) }
								onChange={ ( checked ) =>
									handleSectionToggle(
										option.value,
										checked
									)
								}
							/>
						) ) }
					</PanelBody>
					<PanelBody title={ __( 'Display', 'style-guide' ) }>
						<SelectControl
							__nextHasNoMarginBottom
							label={ __(
								'Color Value Format',
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
