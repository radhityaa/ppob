## Installations

```
git clone https://github.com/radhityaa/ppob.git
```
```
composer install
```
```
cp .env.example .env
```
```
nano .env
```

# Edit you setting on env

```
set APP_ENV=production
```
```
TRX_TYPE=production
```
```
WA_ADMIN_NUMBER=(required)
```
```
setting your database connection
```

```
php artisan migrate:fresh --seed
```

- Export message_templates.sql to database

```
php artisan key:generate
```
```
php artisan storage:link
```

# Add permissions
```
chown -R www-data:www-data /var/www/<domain>/storage
```
```
chown -R www-data:www-data /var/www/<domain>/public/assets
```
```
sudo chown -R www:data-www:data /var/www/<domain>/bootstrap/cache
```

# Setting you nginx
```
nano /etc/nginx/sites-available/<domain>
```
- Copy this code
```
server {
    listen 80;
    listen [::]:80;
    server_name example.com;
    root /var/www/example.com/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location /api/ { # For API
    	proxy_pass http://localhost:4000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }

    location /socket.io/ { # For Websocket
    	proxy_pass http://127.0.0.1:5000; # arahkan ke server Socket.IO di port 5000
    	proxy_http_version 1.1;
    	proxy_set_header Upgrade $http_upgrade;
    	proxy_set_header Connection "upgrade";
    	proxy_set_header Host $host;
    	proxy_cache_bypass $http_upgrade;
    	proxy_set_header X-Real-IP $remote_addr;
    	proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    	proxy_set_header X-Forwarded-Proto $scheme;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}

```
```
ln -s /etc/nginx/sites-available/<sitename> /etc/nginx/sites-enabled/
```
```
nginx -t
```
```
systemctl restart nginx
```

# Running server whatsapp gateway for notification
- Using pm2 in vps ubuntu

```
cd /whatsapp
```
```
npm install
```
```
pm2 start src/main.js
```
