# eRep
eRepublik clone

# Screenshots

Reminder: I don't care about the design, i've only made the backend.

![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/1.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/2.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/3.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/4.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/5.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/6.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/7.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/8.jpg)
![Image](https://raw.githubusercontent.com/tetreum/erep/master/screenshots/9.jpg)

# Finished features
- Chat
- Create & work on companies
- Train
- Publish/view/vote articles
- Congress
- Political parties
- Storage
- Sell/buy items
- Multiple currencies
- Private messages

# Install
- composer install
- npm install
- mv conf.sample.php conf.php
- grunt
- import db.sql in your mysql

# Requirements
- PHP >= 7.0
- MySQL / PostgreSQL (i think db.sql it's in Postgre's format)
- Friendly urls/mod rewrite
- gruntjs

# Nginx vhost setup example
```
server {
    listen   80;
    server_name erepublik.dev;

    root /var/www/erepublik/htdocs;
    index index.html index.htm index.php;

    charset utf-8;
    sendfile off;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    ## The "application" requests should be processed by Slim
    location ~ \.php$ {
                try_files $uri =404;
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                # NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

                # With php5-fpm:
                fastcgi_pass unix:/run/php/php7.0-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                include fastcgi_params;
    }
}
```



