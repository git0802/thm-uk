set -e
echo $(date)
if [ ! -f /tmp/fpmlock ]
then
    touch /tmp/fpmlock
    chmod 666 /tmp/fpmlock
fi

cd ${DEPLOY_PATH}
git pull origin master
composer install --no-interaction --prefer-dist --optimize-autoloader
# npm install
# npm run prod

( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service php7.4-fpm reload ) 9>/tmp/fpmlock

if [ -f artisan ]; then
    php artisan migrate --force
fi
