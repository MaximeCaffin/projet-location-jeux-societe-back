---  ETAPE 00  -- 

connexion SSH
```
ssh student@PSEUDOGH-server.eddi.cloud
```

```
sudo apt install -y apache2 php8.3 libapache2-mod-php8.3 mariadb-server php-mysql

Si cela ne marche pas, faire :
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.3
```

config mysql
```
sudo mysql_secure_installation
sudo mysql
CREATE USER 'explorateur'@'localhost' IDENTIFIED BY 'Ereul9Aeng';
GRANT ALL PRIVILEGES ON *.* TO 'explorateur'@'localhost' WITH GRANT OPTION;
```

adminer
```
sudo mkdir /var/www/html/adminer
cd /var/www/html/adminer
sudo wget https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1-mysql.php
sudo mv adminer-4.8.1-mysql.php index.php
```

permissions
```
sudo chown -R student:www-data /var/www/html
sudo chmod -R g+w /var/www/html
sudo chmod -R 775 /var/www/html
```

suppression index.html
```
rm /var/www/html/index.html
```


---  ETAPE 01  -- 

1. Se connecter à la VM Server
2. Se déplacer dans le bon dossier : sudo mkdir /var/www/html/NomduDossier et cd /var/www/html/nomdudossier
3. Executer commande génération keygen : ssh-keygen -t ed25519 -C "your_email@example.com". Entréé pour valider emplacement par défault.
4. Afficher la clé avec la commande suivante : cat ~/.ssh/id_ed25519.pub
5. Se connecter compte [github ](https://github.com/settings/keys). Appuyer sur "new SSH key", donner un nom, coller la clé, valider.
6. Si non placé dans le bon dossier, cd /var/www/html. Sinon, Executer commande git clone du projet.
7. Bravo, site déployé!


--  ETAPE 02 FRONT --

1.  Vérification liste site disponible cd /etc/apache2/sites-available/
        ls
2. Tappez commande sudo a2dissite NOMDUSITE.conf (si besoin désactivé site)
3. sudo nano NOMDUSITE-front.conf
<VirtualHost *:80>
    # Adresse (nom de domaine) sur laquelle on va aller pour accéder à l'application
    # ATTENTION, bien remplacer {{PSEUDO-GITHUB}} par votre pseudo GitHub !
    ServerName front.{{PSEUDO-GITHUB}}-server.eddi.cloud
    # Chemin de l'application (racine du serveur web)
    # ATTENTION, bien remplacer {{PSEUDO-GITHUB}} par votre pseudo GitHub !
    DocumentRoot /var/www/html/S08-PHP-Pomodor-O-{{PSEUDO-GITHUB}}/dist

    # Emplacement logs Apache
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost> 

# Adresse (nom de domaine) sur laquelle on va aller pour accéder à l'application
4. Verification cat /etc/apache2/sites-available/NOMDUSITE-front.conf
5. sudo a2ensite NOMDUSITE-front
6. Relance serveur : sudo systemctl reload apache2
7. Checker si tout s'est bien passé : cat /etc/apache2/sites-enabled/NOMDUSITE-front.conf


--  ETAPE 03 BACK --

1. sudo nano /etc/apache2/sites-available/NOMDUSITE-back.conf
2. <VirtualHost *:80>
    # Adresse (nom de domaine) sur laquelle on va aller pour accéder à l'application
    # ATTENTION, bien remplacer {{PSEUDO-GITHUB}} par votre pseudo GitHub !
    ServerName backend.{{PSEUDO-GITHUB}}-server.eddi.cloud
    # Chemin de l'application (racine du serveur web)
    # ATTENTION, bien remplacer {{PSEUDO-GITHUB}} par votre pseudo GitHub !
    DocumentRoot /var/www/html/S08-PHP-Pomodor-O-{{PSEUDO-GITHUB}}/back/public

    # Emplacement logs Apache
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
3. sudo a2ensite NOMDUSITE-back
4. sudo systemctl reload apache2


-- ETAPE 04  --

 vhost adminer
```
<VirtualHost *:80>
    # Adresse (nom de domaine) sur laquelle on va aller pour accéder à l'application
    # ATTENTION, bien remplacer {{PSEUDO-GITHUB}} par votre pseudo GitHub !
    ServerName adminer.{{PSEUDO-GITHUB}}-server.eddi.cloud
    # Chemin de l'application (racine du serveur web)
    DocumentRoot /var/www/html/adminer

    # Emplacement logs Apache
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```


pour activer https :
passer la VM serveur en publique depuis kourou et lancer :
```
sudo snap install certbot --classic
sudo certbot --apache
```
la deuxième commande demande de choisir pour quel vhost il faut activer https

sudo apt install php8.3-mysql
sudo service apache2 restart


-- ETAPE 05 -- FRONT

1. yarn install
2. check the wanted version of Node.js
3. 
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
# download and install Node.js
nvm install 20
# verifies the right Node.js version is in the environment
node -v # should print `v20.13.1`
# verifies the right NPM version is in the environment
npm -v # should print `10.5.2`
4. yarn add redux react-redux @redux-devtools/extension



-- ETAPE 06 -- APACHE
   
1. Ouvrir le fichier de configuration Apache : Vous pouvez généralement trouver la configuration principale d'Apache dans /etc/apache2/apache2.conf ou dans le fichier de configuration spécifique au site dans /etc/apache2/sites-available/.
2. Allez sur etc/apache2 puis taper commande sudo nano apache2.conf
3. Recherche AllOverrite puis enlever none et ajouter All
4. Redémarrer Apache : sudo systemctl restart apache2
5. vérifier notre environnement de production : sudo nano .env.production
6. si ok, copier à l'environnement local : cp .env.production .env.local
7. verifier que les migrations ont été faites : bin/console d:d:c


-- ETAPE 07 -- Installation et clone du front et back

1. cd /var/www/html/NomDuDossier-back
2. Git clone URL_DÉPOT_back + composer update + php --ini
3. cd /var/www/html/NomDuDossier-front
4. Git clone URL_DÉPOT_front =  rm yarn.lock (si besoin)+ yarn install + nvm install 20 + curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash + source ~/.bashrc + node -v # should print v20.13.1 + yarn add redux react-redux @redux-devtools/extension + yarn build