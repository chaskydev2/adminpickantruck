#!/bin/bash

# Este script genera los archivos del favicon

mkdir -p /Users/marioreque/Documents/04-pickntruck/pincktries/pickn6admin/public/favicon

# Crear el favicon usando Font Awesome y convertirlo a PNG
cat > /Users/marioreque/Documents/04-pickntruck/pincktries/pickn6admin/public/favicon/source.svg << EOF
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#4299e1">
  <path d="M64 32C28.7 32 0 60.7 0 96v32H448V96c0-35.3-28.7-64-64-64H64zm416 64H32V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM48 304h80c13.3 0 24 10.7 24 24v80c0 13.3-10.7 24-24 24H48c-13.3 0-24-10.7-24-24V328c0-13.3 10.7-24 24-24zm336-48c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H288c-17.7 0-32-14.3-32-32V288c0-17.7 14.3-32 32-32h96zM152 352h56c13.3 0 24 10.7 24 24s-10.7 24-24 24H152c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>
</svg>
EOF

# Crea un archivo .htaccess para mejorar la entrega de favicon
cat > /Users/marioreque/Documents/04-pickntruck/pincktries/pickn6admin/public/favicon/.htaccess << EOF
<IfModule mod_headers.c>
    <FilesMatch "\.(ico|png)$">
        Header set Cache-Control "max-age=2592000, public"
    </FilesMatch>
</IfModule>
EOF

# Crea un archivo webmanifest
cat > /Users/marioreque/Documents/04-pickntruck/pincktries/pickn6admin/public/favicon/site.webmanifest << EOF
{
    "name": "Pickntruck Admin",
    "short_name": "Pickntruck",
    "icons": [
        {
            "src": "/favicon/android-chrome-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/favicon/android-chrome-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ],
    "theme_color": "#1A202C",
    "background_color": "#1A202C",
    "display": "standalone"
}
EOF

echo "Archivos de favicon generados correctamente. Por favor, utiliza ImageMagick o una herramienta en línea para convertir el SVG en PNG en los tamaños necesarios."
