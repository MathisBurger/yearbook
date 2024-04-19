rm .env
echo "DATABASE_URL=$DATABASE_URL" >> .env
echo "APP_ENV=prod" >> .env
echo "APP_SECRET=81a1c2b0472a6ab9a62a56d74d1c8eca" >> .env
if [ -d "/var/www/html/var" ]; then
  mkdir "/var/www/html/var"
fi
chown -R www-data:www-data /var/www/html/var