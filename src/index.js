import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import Edit from './blocks/multi-slider/edit';

registerBlockType('multi-slider/slider-block', {
    title: __('Multi Slider Interactivo', 'multi-slider-react'),
    description: __('Bloque de slider interactivo con categorías', 'multi-slider-react'),
    category: 'widgets',
    icon: 'slides',
    keywords: [__('slider', 'multi-slider-react'), __('categorías', 'multi-slider-react')],
    attributes: {
        items: {
            type: 'array',
            default: []
        },
        primaryColor: {
            type: 'string',
            default: '#ff6b35'
        }
    },
    edit: Edit,
    save: () => null, // Renderizado dinámico en PHP
});
