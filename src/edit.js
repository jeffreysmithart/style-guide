/**
 * WordPress dependencies
 *
 */
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Panel, PanelBody } from '@wordpress/components';
import { MultiSelectControl } from '@codeamp/block-components';

import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const { selectedSections } = attributes;
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps}>
			<InspectorControls>
				<Panel>
					<PanelBody title={ __( 'Settings' ) }>
						<MultiSelectControl
							value={ selectedSections }
							onChange={ ( value ) =>
								setAttributes( { selectedSections: value } )
							}
							options={ [
								{
									label: 'Color Palette',
									value: 'color-palette',
								},
								{ label: 'Font Sizes', value: 'font-sizes' },
								{
									label: 'Spacing Scale',
									value: 'spacing-scale',
								},
								{ label: 'Shadows', value: 'shadows' },
								{ label: 'Gradients', value: 'gradients' },
							] }
							label={ __( 'Select Sections' ) }
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
