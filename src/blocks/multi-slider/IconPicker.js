import { Component } from '@wordpress/element';
import { Button, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

class IconPicker extends Component {
    constructor(props) {
        super(props);
        this.state = {
            searchTerm: '',
            showPicker: false
        };

        // Lista de iconos populares de Font Awesome
        this.icons = [
            { name: 'Hogar', class: 'fas fa-home' },
            { name: 'Grifería', class: 'fas fa-faucet' },
            { name: 'Herramientas', class: 'fas fa-wrench' },
            { name: 'Llave', class: 'fas fa-key' },
            { name: 'Iluminación', class: 'fas fa-lightbulb' },
            { name: 'Bombilla', class: 'fas fa-light-ceiling' },
            { name: 'Aperturas', class: 'fas fa-door-open' },
            { name: 'Puerta', class: 'fas fa-door-closed' },
            { name: 'Muebles', class: 'fas fa-couch' },
            { name: 'Silla', class: 'fas fa-chair' },
            { name: 'Cama', class: 'fas fa-bed' },
            { name: 'Cocina', class: 'fas fa-utensils' },
            { name: 'Refrigerador', class: 'fas fa-refrigerator' },
            { name: 'Baño', class: 'fas fa-bath' },
            { name: 'Ducha', class: 'fas fa-shower' },
            { name: 'Inodoro', class: 'fas fa-toilet' },
            { name: 'Lavabo', class: 'fas fa-sink' },
            { name: 'Pintura', class: 'fas fa-paint-roller' },
            { name: 'Martillo', class: 'fas fa-hammer' },
            { name: 'Destornillador', class: 'fas fa-screwdriver' },
            { name: 'Taladro', class: 'fas fa-drill' },
            { name: 'Sierra', class: 'fas fa-saw' },
            { name: 'Escalera', class: 'fas fa-ladder' },
            { name: 'Caja de herramientas', class: 'fas fa-toolbox' },
            { name: 'Llave inglesa', class: 'fas fa-wrench-simple' },
            { name: 'Enchufe', class: 'fas fa-plug' },
            { name: 'Bombilla LED', class: 'fas fa-lightbulb-on' },
            { name: 'Lámpara', class: 'fas fa-lamp' },
            { name: 'Ventana', class: 'fas fa-window' },
            { name: 'Edificio', class: 'fas fa-building' },
            { name: 'Casa', class: 'fas fa-house' },
            { name: 'Herramientas construcción', class: 'fas fa-tools' },
            { name: 'Tuerca', class: 'fas fa-nut' },
            { name: 'Tornillo', class: 'fas fa-bolt' },
            { name: 'Regla', class: 'fas fa-ruler' },
            { name: 'Nivel', class: 'fas fa-level' },
            { name: 'Cinta métrica', class: 'fas fa-ruler-combined' },
            { name: 'Cubo', class: 'fas fa-bucket' },
            { name: 'Espátula', class: 'fas fa-trowel' },
            { name: 'Pincel', class: 'fas fa-paintbrush' },
            { name: 'Carrito', class: 'fas fa-shopping-cart' },
            { name: 'Cesta', class: 'fas fa-shopping-basket' },
            { name: 'Estrella', class: 'fas fa-star' },
            { name: 'Corazón', class: 'fas fa-heart' },
            { name: 'Usuario', class: 'fas fa-user' },
            { name: 'Configuración', class: 'fas fa-cog' },
            { name: 'Check', class: 'fas fa-check' },
            { name: 'Cerrar', class: 'fas fa-times' },
            { name: 'Buscar', class: 'fas fa-search' },
            { name: 'Menú', class: 'fas fa-bars' },
        ];
    }

    togglePicker = () => {
        this.setState({ showPicker: !this.state.showPicker });
    }

    selectIcon = (iconClass) => {
        this.props.onSelectIcon(iconClass);
        this.setState({ showPicker: false });
    }

    render() {
        const { selectedIcon } = this.props;
        const { searchTerm, showPicker } = this.state;

        const filteredIcons = this.icons.filter(icon =>
            icon.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            icon.class.toLowerCase().includes(searchTerm.toLowerCase())
        );

        return (
            <div className="multi-slider-icon-picker">
                <label>{__('Icono', 'multi-slider-react')}</label>
                <div className="multi-slider-icon-picker-selected">
                    <div className="multi-slider-icon-display">
                        <i className={selectedIcon}></i>
                    </div>
                    <Button isSecondary onClick={this.togglePicker}>
                        {__('Cambiar Icono', 'multi-slider-react')}
                    </Button>
                </div>

                {showPicker && (
                    <div className="multi-slider-icon-picker-dropdown">
                        <TextControl
                            placeholder={__('Buscar icono...', 'multi-slider-react')}
                            value={searchTerm}
                            onChange={(value) => this.setState({ searchTerm: value })}
                        />
                        <div className="multi-slider-icon-picker-grid">
                            {filteredIcons.map((icon, index) => (
                                <button
                                    key={index}
                                    className={`multi-slider-icon-picker-item ${selectedIcon === icon.class ? 'selected' : ''}`}
                                    onClick={() => this.selectIcon(icon.class)}
                                    title={icon.name}
                                    type="button"
                                >
                                    <i className={icon.class}></i>
                                </button>
                            ))}
                        </div>
                        {filteredIcons.length === 0 && (
                            <p className="multi-slider-no-icons">{__('No se encontraron iconos', 'multi-slider-react')}</p>
                        )}
                    </div>
                )}
            </div>
        );
    }
}

export default IconPicker;
