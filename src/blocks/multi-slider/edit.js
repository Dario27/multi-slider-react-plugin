import { Component } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button, TextControl, Modal } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import IconPicker from './IconPicker';

class Edit extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isModalOpen: false,
            editingIndex: null,
            currentItem: {
                name: '',
                link: '#',
                icon: 'fas fa-home'
            }
        };
    }

    openModal = (index = null) => {
        if (index !== null) {
            this.setState({
                isModalOpen: true,
                editingIndex: index,
                currentItem: { ...this.props.attributes.items[index] }
            });
        } else {
            this.setState({
                isModalOpen: true,
                editingIndex: null,
                currentItem: {
                    name: '',
                    link: '#',
                    icon: 'fas fa-home'
                }
            });
        }
    }

    closeModal = () => {
        this.setState({
            isModalOpen: false,
            editingIndex: null,
            currentItem: {
                name: '',
                link: '#',
                icon: 'fas fa-home'
            }
        });
    }

    addOrUpdateItem = () => {
        const { attributes, setAttributes } = this.props;
        const { editingIndex, currentItem } = this.state;
        const items = [...attributes.items];

        if (editingIndex !== null) {
            items[editingIndex] = currentItem;
        } else {
            items.push(currentItem);
        }

        setAttributes({ items });
        this.closeModal();
    }

    deleteItem = (index) => {
        const { attributes, setAttributes } = this.props;
        const items = attributes.items.filter((_, i) => i !== index);
        setAttributes({ items });
    }

    updateCurrentItem = (field, value) => {
        this.setState({
            currentItem: {
                ...this.state.currentItem,
                [field]: value
            }
        });
    }

    render() {
        const { attributes } = this.props;
        const { items } = attributes;
        const { isModalOpen, currentItem, editingIndex } = this.state;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Configuración del Slider', 'multi-slider-react')}>
                        <p>{__('Usa el panel principal para añadir items', 'multi-slider-react')}</p>
                    </PanelBody>
                </InspectorControls>

                <div className="multi-slider-editor">
                    <h3>{__('Añadir nuevo item', 'multi-slider-react')}</h3>

                    <Button
                        isPrimary
                        onClick={() => this.openModal()}
                        className="multi-slider-add-button"
                    >
                        + {__('Añadir Item', 'multi-slider-react')}
                    </Button>

                    <div className="multi-slider-items-grid">
                        {items.map((item, index) => (
                            <div key={index} className="multi-slider-item-card">
                                <div className="multi-slider-item-header">
                                    <strong>{__('NOMBRE', 'multi-slider-react')}</strong>
                                    <TextControl
                                        value={item.name}
                                        onChange={(value) => {
                                            const newItems = [...items];
                                            newItems[index].name = value;
                                            this.props.setAttributes({ items: newItems });
                                        }}
                                    />
                                </div>
                                <div className="multi-slider-item-body">
                                    <strong>{__('ENLACE', 'multi-slider-react')}</strong>
                                    <TextControl
                                        value={item.link}
                                        onChange={(value) => {
                                            const newItems = [...items];
                                            newItems[index].link = value;
                                            this.props.setAttributes({ items: newItems });
                                        }}
                                        placeholder="#"
                                    />
                                </div>
                                <div className="multi-slider-item-footer">
                                    <Button
                                        isSecondary
                                        onClick={() => this.openModal(index)}
                                        className="multi-slider-icon-button"
                                    >
                                        <i className="fas fa-pen"></i> {__('Seleccionar Icono', 'multi-slider-react')}
                                    </Button>
                                    <div className="multi-slider-icon-preview">
                                        <i className={item.icon}></i>
                                    </div>
                                </div>
                                <Button
                                    isDestructive
                                    onClick={() => this.deleteItem(index)}
                                    className="multi-slider-delete-button"
                                >
                                    <i className="fas fa-trash"></i> {__('Eliminar', 'multi-slider-react')}
                                </Button>
                            </div>
                        ))}
                    </div>

                    {isModalOpen && (
                        <Modal
                            title={editingIndex !== null ? __('Editar Item', 'multi-slider-react') : __('Añadir Item', 'multi-slider-react')}
                            onRequestClose={this.closeModal}
                            className="multi-slider-modal"
                        >
                            <div className="multi-slider-modal-content">
                                <TextControl
                                    label={__('Nombre', 'multi-slider-react')}
                                    value={currentItem.name}
                                    onChange={(value) => this.updateCurrentItem('name', value)}
                                />
                                <TextControl
                                    label={__('Enlace', 'multi-slider-react')}
                                    value={currentItem.link}
                                    onChange={(value) => this.updateCurrentItem('link', value)}
                                    placeholder="#"
                                />
                                <IconPicker
                                    selectedIcon={currentItem.icon}
                                    onSelectIcon={(icon) => this.updateCurrentItem('icon', icon)}
                                />
                                <div className="multi-slider-modal-actions">
                                    <Button isPrimary onClick={this.addOrUpdateItem}>
                                        {editingIndex !== null ? __('Actualizar', 'multi-slider-react') : __('Añadir', 'multi-slider-react')}
                                    </Button>
                                    <Button isSecondary onClick={this.closeModal}>
                                        {__('Cancelar', 'multi-slider-react')}
                                    </Button>
                                </div>
                            </div>
                        </Modal>
                    )}

                    {items.length > 0 && (
                        <div className="multi-slider-preview">
                            <h4>{__('Vista Previa', 'multi-slider-react')}</h4>
                            <div className="multi-slider-preview-items">
                                {items.map((item, index) => (
                                    <div key={index} className="multi-slider-preview-item">
                                        <div className="multi-slider-preview-icon">
                                            <i className={item.icon}></i>
                                        </div>
                                        <span>{item.name}</span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </>
        );
    }
}

export default Edit;
