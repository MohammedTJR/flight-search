#!/usr/bin/env bash
#
# Configuración única del servidor para las tareas en segundo plano de FlyLow.
# Ejecutar UNA vez como root en el servidor:  bash /var/www/flight-search/deploy/setup-server.sh
# Es idempotente: se puede repetir sin problema.
#
#  - Worker de la cola (alertas de precio): servicio systemd "flylow-queue" como www-data.
#  - Scheduler: cron de www-data (cada minuto), y se retira el de root si existía
#    (root crea ficheros de storage que PHP-FPM luego no puede escribir).
#
set -euo pipefail

APP=/var/www/flight-search
WEB_USER=www-data
PHP_BIN="$(command -v php)"

echo "==> servicio systemd flylow-queue"
cat > /etc/systemd/system/flylow-queue.service <<UNIT
[Unit]
Description=FlyLow - worker de la cola de Laravel
After=network.target

[Service]
User=${WEB_USER}
Group=${WEB_USER}
WorkingDirectory=${APP}
ExecStart=${PHP_BIN} artisan queue:work --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=5

[Install]
WantedBy=multi-user.target
UNIT
systemctl daemon-reload
systemctl enable --now flylow-queue
systemctl restart flylow-queue

echo "==> cron del scheduler como ${WEB_USER}"
CRON_LINE="* * * * * cd ${APP} && ${PHP_BIN} artisan schedule:run >> /dev/null 2>&1"
{ crontab -l -u "${WEB_USER}" 2>/dev/null | grep -v 'schedule:run' || true; echo "${CRON_LINE}"; } | crontab -u "${WEB_USER}" -
{ crontab -l -u root 2>/dev/null | grep -v 'schedule:run' || true; } | crontab -u root -

echo "==> storage con el dueño correcto"
chown -R "${WEB_USER}:${WEB_USER}" "${APP}/storage" "${APP}/bootstrap/cache"

echo
echo "Listo. Comprobación:"
systemctl --no-pager --lines=0 status flylow-queue | head -3
echo "cron www-data:"; crontab -l -u "${WEB_USER}" | grep schedule:run
