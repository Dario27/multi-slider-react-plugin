# Multi Slider React Plugin para WordPress

Plugin de WordPress desarrollado con React para crear sliders interactivos de categorías compatibles con Gutenberg.

## Características

- ✅ Compatible con bloques de Gutenberg
- ✅ Interfaz de administración intuitiva en el editor de páginas
- ✅ Sistema de navegación con flechas y dots
- ✅ Totalmente responsive (móvil, tablet, desktop)
- ✅ Iconografía con Font Awesome 7.0
- ✅ Selector de iconos integrado con búsqueda
- ✅ Animaciones suaves y transiciones
- ✅ Fácil de personalizar y extender

## Requisitos

- WordPress 5.0 o superior
- PHP 7.4 o superior
- Node.js 14 o superior
- npm o yarn

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/Dario27/multi-slider-react-plugin.git
cd multi-slider-react-plugin
```

### 2. Instalar dependencias

```bash
npm install
```

### 3. Compilar el plugin

```bash
npm run build
```

Para desarrollo con watch mode:

```bash
npm run dev
```

### 4. Instalar en WordPress

1. Copia la carpeta completa del plugin a `wp-content/plugins/`
2. Ve al panel de administración de WordPress
3. Navega a Plugins → Plugins instalados
4. Activa "Multi Slider React"

## Uso

### Añadir el bloque a una página

1. Crea o edita una página en WordPress
2. Haz clic en el botón "+" para añadir un nuevo bloque
3. Busca "Multi Slider Interactivo"
4. Añade el bloque a tu página

### Configurar items del slider

1. Haz clic en el botón "+ Añadir Item"
2. Rellena los campos:
   - **Nombre**: El texto que se mostrará debajo del icono
   - **Enlace**: La URL a la que dirigirá el item (ej: `/categoria/hogar` o `#`)
   - **Icono**: Haz clic en "Seleccionar Icono" para elegir de la biblioteca Font Awesome

3. Puedes editar items existentes directamente desde las tarjetas
4. Usa el botón "Eliminar" para quitar items

### Vista previa

El editor mostrará una vista previa de cómo se verá el slider en tu página.

## Estructura del proyecto

```
multi-slider-react-plugin/
├── build/                      # Archivos compilados (generados por webpack)
├── src/
│   ├── blocks/
│   │   └── multi-slider/
│   │       ├── edit.js        # Componente del editor
│   │       ├── IconPicker.js  # Selector de iconos
│   │       ├── editor.css     # Estilos del editor
│   │       └── style.css      # Estilos del frontend
│   └── index.js               # Punto de entrada principal
├── multi-slider-react.php     # Archivo principal del plugin
├── package.json               # Dependencias y scripts
├── webpack.config.js          # Configuración de webpack
└── README.md                  # Este archivo
```

## Personalización

### Cambiar colores

Edita `src/blocks/multi-slider/style.css` y modifica las siguientes variables:

```css
/* Color principal (naranja) */
color: #ff6b35;
background: #ff6b35;

/* Color de fondo */
background: #f5f5f5;
```

### Añadir más iconos

Edita `src/blocks/multi-slider/IconPicker.js` y añade más iconos al array `this.icons`:

```javascript
{ name: 'Mi Icono', class: 'fas fa-icon-name' }
```

### Cambiar items visibles por defecto

Edita la función `getItemsPerView()` en `multi-slider-react.php`:

```javascript
function getItemsPerView() {
    if (window.innerWidth < 768) return 2;   // Móvil
    if (window.innerWidth < 1024) return 4;  // Tablet
    return 6;                                 // Desktop
}
```

## Responsive Breakpoints

| Dispositivo | Ancho | Items visibles |
|-------------|-------|----------------|
| Móvil       | < 768px  | 2 |
| Tablet      | 768px - 1024px | 4 |
| Desktop     | > 1024px | 6 |

## Scripts disponibles

- `npm run build` - Compila el plugin para producción
- `npm run dev` - Compila en modo desarrollo con watch
- `npm start` - Alias de `npm run dev`

## Iconos disponibles

El plugin incluye una selección curada de iconos de Font Awesome 7.0, incluyendo:

- Hogar y construcción
- Herramientas
- Iluminación
- Muebles
- Grifería y baño
- Y muchos más...

Todos los iconos son buscables desde el selector integrado.

## Soporte y contribuciones

Si encuentras algún problema o tienes sugerencias:

1. Abre un issue en GitHub
2. Envía un pull request con mejoras
3. Contacta al desarrollador

## Changelog

### Version 1.0.0
- Lanzamiento inicial
- Bloque de Gutenberg con React
- Selector de iconos Font Awesome
- Sistema de navegación con flechas y dots
- Diseño responsive
- Interfaz de administración completa

## Licencia

MIT License - Ver archivo LICENSE para más detalles

## Créditos

- Font Awesome para los iconos: https://fontawesome.com
- WordPress y Gutenberg por la plataforma de bloques
- React por el framework UI

## Autor

Desarrollado por Dario27

---

¿Necesitas ayuda? Abre un issue en el repositorio de GitHub.
