# Important
Make sure the file `otpl.json` or `db.json` is not accessible from the internet, it will contain the stored passwords in plain text.

# Docker setup
    git clone https://github.com/JeftaDirksen/OTPL.git
    cd OTPL
    docker build -t otpl .
    docker run -d -p 80:80 otpl

# Manual setup on linux
    su -
    
    apt install -y git libapache2-mod-php
    
    git clone https://github.com/JeftaDirksen/OTPL.git /var/www/otpl
    cp /var/www/otpl/src/otpl.config.example.php otpl.config.php
    chown -R www-data /var/www/otpl
    
    cat >/etc/apache2/sites-available/otpl.conf <<EOL
    <VirtualHost *:80>
      DocumentRoot /var/www/otpl/src
      ErrorLog \${APACHE_LOG_DIR}/error.log
      CustomLog \${APACHE_LOG_DIR}/access.log combined
      <Directory /var/www/otpl/src/>
        Require all granted
        DirectoryIndex otpl.php
        <Files "*.json">
          Require all denied
        </Files>
      </Directory>
    </VirtualHost>
    EOL
    
    a2ensite otpl.conf
    a2dissite 000-default.conf
    systemctl reload apache2
