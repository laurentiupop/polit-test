# polit-test

to configure on local, run composer file, I installed extra stuff there as:
-symfony/serializer
-symfony/http-client
-symfony/doctrine-messenger
-symfony/messenger

for thesting on local, I made an apache config, as this:

<VirtualHost *:80>
        ServerName polit.test
        DocumentRoot /srv/workspace/polit-test/public

        <Directory /srv/workspace/polit-test/public>
                AllowOverride None
                Order Allow,Deny
                Allow from All
                Require all granted

                <IfModule mod_rewrite.c>
                  Options -MultiViews
                  RewriteEngine On
                  RewriteCond %{REQUEST_FILENAME} !-f
                  RewriteRule ^(.*)$ index.php [QSA,L]
                </IfModule>
        </Directory>

        <Directory /srv/workspace/polit-test/public/bundles>
                <IfModule mod_rewrite.c>
                        RewriteEngine Off
                </IfModule>
        </Directory>


        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>

and use to get JSON requests from task 3 like this:
- for list of MEPs : http://polit.test/api/persons
- for specific MEP by id : http://polit.test/api/person/<id>

Task1 and 2 and 3 - use in command line: php bin/console app:import-meps

For 4 I user: php bin/console messenger:consume async -vv (but is not finished)

