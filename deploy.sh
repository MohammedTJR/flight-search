#!/usr/bin/env bash
#
# Script de despliegue de FlyLow en producción.
# Lo ejecuta GitHub Actions por SSH tras cada push a main
# (el workflow hace `git pull` ANTES de llamar a este script).
#
# Optimizado: solo ejecuta cada paso si los ficheros relevantes
# cambiaron en el último pull, para que los despliegues sean rápidos.
#
set -euo pipefail

cd /var/www/flight-search

echo "==> Commit desplegado"
git log --oneline -1

# Ficheros cambiados en el último pull (HEAD@{1} = estado previo al pull).
# Si no se puede determinar, se hace un despliegue completo por seguridad.
CHANGED="$(git diff --name-only 'HEAD@{1}' HEAD 2>/dev/null || true)"
if [ -z "$CHANGED" ]; then
    echo "   (no se detectaron cambios concretos; despliegue completo)"
    FULL=1
else
    FULL=0
    echo "   Cambios:"
    echo "$CHANGED" | sed 's/^/     - /'
fi

changed() { [ "$FULL" = "1" ] || echo "$CHANGED" | grep -qE "$1"; }

# --- Dependencias PHP: solo si cambió composer.lock/json ---
if changed '^composer\.(json|lock)$'; then
    if command -v composer >/dev/null 2>&1; then
        echo "==> composer install"
        composer install --no-dev --optimize-autoloader --no-interaction
    else
        echo "!! composer no encontrado, se omite"
    fi
else
    echo "==> composer: sin cambios, se omite"
fi

# --- Assets: build solo si cambiaron las fuentes de Vite o sus configs ---
NEED_BUILD=0
if changed '^(resources/(sass|js)/|vite\.config\.js|tailwind\.config\.js|postcss\.config\.js|package(-lock)?\.json)'; then
    NEED_BUILD=1
fi

if [ "$NEED_BUILD" = "1" ]; then
    # npm install incremental si cambiaron las dependencias o falta node_modules
    if changed '^package(-lock)?\.json$' || [ ! -d node_modules ]; then
        echo "==> npm install"
        npm install --no-audit --no-fund
    fi
    echo "==> npm run build"
    npm run build
else
    echo "==> assets: sin cambios, no se recompila"
fi

# --- Migraciones: solo si hay cambios en database/migrations ---
if changed '^database/migrations/'; then
    echo "==> migraciones"
    php artisan migrate --force
else
    echo "==> migraciones: sin cambios, se omite"
fi

# --- Cachés de Laravel (baratas, siempre) ---
echo "==> cachés"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- Permisos: este script corre como root, así que las cachés y vistas compiladas
#     quedan con dueño root y PHP-FPM no puede reescribirlas (500 al cambiar una vista).
#     Se devuelven al usuario con el que corre PHP-FPM (por defecto www-data). ---
WEB_USER="$(ps -eo user,comm | awk '$2 ~ /^php-fpm/ && $1 != "root" {print $1; exit}' || true)"
WEB_USER="${WEB_USER:-www-data}"
echo "==> permisos de storage y bootstrap/cache para $WEB_USER"
chown -R "$WEB_USER":"$WEB_USER" storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# --- Recargar PHP-FPM para limpiar opcache ---
echo "==> recargar php-fpm"
systemctl reload php8.3-fpm 2>/dev/null || true

echo "==> DESPLIEGUE COMPLETADO"
