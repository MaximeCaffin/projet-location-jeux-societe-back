# Déployer Directus

> ℹ️ Tout au long de ce tutoriel, tu rencontreras des sections "SPOILER" que tu pourras déplier pour obtenir des explications sur ce que tu fais et pourquoi tu le fais.

## Étape 0 : La VM Server Kourou

<details>
<summary>SPOILER: C'est quoi la VM Server Kourou ?</summary>

Si tu dormais pendant la saison 8 du socle (ou si tu as beaucoup dormi depuis), voici un bref rappel de ce qu'est la VM Server Kourou :

> O'clock met à ta disposition un serveur virtuel dans le ☁️ cloud ☁️ pour t'aider à t'entraîner au déploiement de tes projets web et à l'administration système. Ce serveur virtuel t'est fourni avec un nom de domaine.
</details>

😉 Rends-toi sur la page d'administration de ta VM Server Kourou, tu en auras besoin pour la suite de ce tutoriel : [https://kourou.oclock.io/ressources/vm-cloud/](https://kourou.oclock.io/ressources/vm-cloud/)

> ℹ️ Certaines commandes de ce tutoriel te demanderont le mot de passe administrateur de ta VM Server Kourou, tu trouveras celui-ci sur la page d'administration.

### Initialiser la VM

<details>
<summary>SPOILER: Premiers pas</summary>

Si tu n'as jamais utilisé la VM Server Kourou ou si tu l'as supprimée, clique sur le bouton "Créer la VM".

Pour repartir d'une VM vierge, clique sur le bouton "Réinstaller la VM".

⚠️ Dans ces deux cas, tu devras créer une nouvelle clé SSH sur la VM Server Kourou pour pouvoir cloner et pull le projet depuis GitHub. Tu trouveras la procédure à suivre dans la [fiche récap dédiée à Git et GitHub](https://kourou.oclock.io/ressources/fiche-recap/git-et-github/#cr%c3%a9er-une-cl%c3%a9-ssh-pour-github).
</details>


Si ta VM est déjà prête, clique sur "Démarrer la VM".

### Se connecter à la VM

Une fois la VM Server démarrée, connecte-toi en SSH. Tu trouveras la commande à utiliser dans ton terminal sur la page d'administration de ta VM, elle devrait ressembler à ceci :

```bash
ssh student@pseudo-server.eddi.cloud
```

⚠️ Tout le temps de ta connexion à la VM, **_ne ferme surtout pas la page d'administration_** car c'est elle qui permet d'autoriser la connexion depuis ton IP !

## Étape 1 : Installer l'environnement de production

### Installation du SGBDR MariaDB (MySQL)

> ⚠️ **Si tu utilises SQLite pour la BDD de Directus, peux sauter cette section et passer directement à [Installation de Apache](#installation-de-apache)**

```bash
sudo apt update
sudo apt install -y mariadb-server mariadb-client
```

<details>
<summary>SPOILER: Détail des commandes</summary>

On commence par mettre à jour les catalogues des logiciels disponibles sur le serveur (la commande `sudo` permet d'excuter la commande avec les droits administrateur) :
```bash
sudo apt update
```

Puis on installe les packages (logiciels) nécessaires pour gérer des bases de données sur le serveur :
```bash
sudo apt install -y mariadb-server mariadb-client
```

Ici, on a choisi d'installer MariaDB qui est une version open source de MySQL. On a besoin du package `mariadb-server` pour avoir un serveur de bases de données, ainsi que des outils fournis par le package `mariadb-client` (notamment la commande `mysql`) pour interagir avec ces bases de données.

L'option `-y` permet de dire au logiciel `apt` qui sert à gérer les packages installés sur le système de ne pas demander confirmation pour installer les packages demandés.
</details>

### Installation de Apache

```bash
sudo apt install -y apache2
```

<details>
<summary>SPOILER: Détail de la commande</summary>

On installe ici le serveur web Apache httpd, qui est un des plus utilisés dans les environnements de production à l'heure actuelle. On pourrait tout-à-fait en installer un autre tel que Nginx ou Caddy.

Comme précédemment, on utilise la commande `apt install` pour installer le paquet logiciel `apache2`. L'option `-y` permet de ne pas avoir à confirmer l'installation a posteriori et la commande est lancée avec les droits administrateur (`sudo`).

> 🤔 _**Mais pourquoi a-t-on besoin d'un serveur web ?**_
>
> _En effet, Directus est fourni avec son propre serveur HTTP qui est fait et tourne avec Node.js, donc il n'y a en théorie pas besoin d'utiliser un autre serveur web pour accéder à Directus une fois qu'il est installé… Sauf que dans la pratique, on préfère éviter d'utiliser un serveur Node.js comme serveur "frontal"._
>
> _Ici, on installe autre logiciel qui n'est qu'un serveur web et dont l'unique rôle sera de recevoir les requêtes HTTP du client et de les passer au serveur de Directus (c'est le principe d'un serveur proxy). Cette technique permettra d'avoir toujours un serveur HTTP qui répond même si le serveur de Directus a crashé, et il sera beaucoup plus simple de configurer HTTPS sur ce proxy que sur le serveur Node.js de Directus._
>
> _Voici, en gros, l'installation que ce tutoriel va te faire mettre en place :_
>
> ```mermaid
> sequenceDiagram
>     participant C as Client (navigateur)
>     participant A as Apache (proxy)
>     participant D as Directus (Node.js)
> 
>     C ->> A: requête HTTP
>     A -->> D: requête HTTP
>     D -->> D: traitement
>     D -->> A: réponse HTTP
>     A ->> C: réponse HTTP
> ```

</details>

### Installation de Node.js

> ℹ️ Tu vas installer Node.js sur ta VM Server Kourou avec [Node Version Manager](https://github.com/nvm-sh/nvm), un outil qui permet d'installer et d'utiliser rapidement n'importe quelle version de Node. Ainsi, tu t'assures que tu pourras exécuter la version de Node.js la mieux adaptée à ton application (Directus requiert actuellement la version 18 mais si tu utilises aussi Svelte, tu auras peut-être besoin d'une version plus récente).

```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
source ~/.bashrc
```

<details>
<summary>SPOILER: Détail des commandes</summary>

D'abord, on télécharge le script d'installation de NVM et on l'exécute avec l'interprète Bash (même programme qui interprète les commandes que tu entres dans ton terminal 😉). Ce script va télécharger NVM, l'installer dans le répertoire personnel de l'utilisateur actuel et ajouter l'alias de commande `nvm` pour ce même utilisateur.
```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
```

On dit à l'interpréteur Bash du terminal de recharger le fichier `.bashrc` de l'utilisateur, car celui-ci contient notamment l'alias de commande `nvm` qu'on va devoir utiliser ensuite. Sans ce rechargement, il faudrait se déconnecter et se reconnecter à la VM pour avoir accès à la commande `nvm`.
```bash
source ~/.bashrc
```
</details>

## Étape 2 : Installer Directus

### Préparation du répertoire d'installation

```bash
sudo chmod -R o+w /var/www
cd /var/www
```

<details>
<summary>SPOILER: Détails de la commande</summary>

Tu vas installer Directus dans le répertoire `/var/www`. Comme le répertoire parent `/var` est protégé en écriture, cette commande doit être lancée avec les droits administrateur (`sudo`).

```bash
sudo mkdir -p /var/www
```

Le répertoire dans lequel tu vas installer Directus est un répertoire protégé en écriture, il faut donc te donner le droit d'y modifier des fichiers et des dossiers.

```bash
sudo chmod -R o+w /var/www
```
On peut décomposer cette commande ainsi :
- `sudo` - on exécute la commande qui suit avec les droits administrateur
- `chmod` - commande qui permet de modifier les droits d'une ressources (fichier ou répertoire)
- `-R` - on indique que la modification s'appliquera aussi aux fichiers et dossiers enfants, par _récursivité_
- `o+w` - il s'agit de la modification des droits
  - `o` - on modifie les droits des "autres utilisateurs", c'est-à-dire ceux qui ne sont pas propriétaires de la ressource
  - `+` - on indique qu'on ajoute les droits qui suivent
  - `w` - droit d'écriture (🇬🇧 _write_ = 🇫🇷 _écrire_)
- `/var/www` - on spécifie le chemin de la ressource dont on modifie les droits, ici c'est le répertoire `/var/www`

Cette commande-ci devrait t'être familière, on ne fait que se placer dans un répertoire précis :
```bash
cd /var/www
```
</details>

### Préparation de la base de données

> ⚠️ **Si tu utilises SQLite pour la BDD de Directus, peux sauter cette section et passer directement à [Installation de Directus](#installation-de-directus)**

Pour créer et configurer la base de données, tu vas utiliser MySQL en ligne de commande :

```bash
sudo mysql -u root
```

<details>
<summary>SPOILER: Détail de la commande</summary>

On utilise ici la commande `mysql` fournie par notre installation du package `mariadb-client` pour se connecter au serveur de bases de données. L'option `-u` permet d'indiquer le nom de l'utilisateur avec lequel on souhaite se connecter.

Comme notre installation de MariaDB/MySQL est "fraîche", le serveur de bases de données ne comporte qu'un seul utilisateur qui a tous les droits : `root`. Cependant, son utilisation est restreinte par défaut.

En effet, `root` ne peut se connecter que via le terminal et seulement si la commande `mysql` est utilisée avec les droits administrateur (via `sudo`). **Tu ne pourras donc pas utiliser l'utilisateur `root` pour te connecter à ta base de données avec ton code !!!**

</details>

Tu devrais maintenant te trouver dans un shell MySQL, c'est un peu comme le terminal de ton SGBDR à l'intérieur de ton terminal.

Crée d'abord la base de données **(⚠️ _remplace `<db>` par le nom de ta BDD !!!_)** :
```sql
CREATE DATABASE <db>;
```

Crée ensuite l'utilisateur qui permettra à ton code de se connecter à ta base de données **(⚠️ _remplace `<user>` par le nom de l'utilisateur et `<password>` par son mot de passe !!!_)** :
```sql
CREATE USER '<user>'@'localhost' IDENTIFIED BY '<password>';
```

Donne les droits à l'utilisateur sur la base de données **(⚠️ _remplace `<db>` par le nom de la BDD et `<user>` par le nom de l'utilisateur !!!_)** :
```sql
GRANT ALL PRIVILEGES ON <db>.* TO '<user>'@'localhost';
```

Recharge les droits :
```sql
FLUSH PRIVILEGES;
```

Sors du shell MySQL :
```sql
EXIT;
```

### Installation de Directus

```bash
nvm exec 18 npm init directus-project@latest directus.site
```

<details>
<summary>SPOILER: Détail de la commande</summary>

Tout d'abord, on fait appel à `nvm exec` pour exécuter une commande avec une version spécifique de Node.js (ici, la version 18). Ceci vient du fait que Directus requiert absolument la version 18 de Node.js, il ne tolère aucune autre version, mais il est possible que tu aies d'autres projets (avec Svelte, notamment) qui nécessitent une version plus récente. NVM te permet donc d'utiliser la Node.js 18 pour installer Directus, tout en restant sur ta version plus récente de Node.js pour les autres choses.

On utilise NPM pour télécharger le package Node `directus-project` qui permet d'initialiser un nouveau projet Directus. En lui donnant un nom de répertoire, on dit au package d'initialiser le projet dans ce répertoire spécifiquement (ici `directus.site`).

La commande créera donc une nouvelle installation de Directus dans le répertoire `/var/www/directus.site`.
</details>

Réponds aux différentes questions dans le terminal pour configurer ton installation de Directus :

> `Choose your database client`
>
> **Si tu utilises SQLite :**
> - Sélectionne `SQLite`
> - Fais "Entrée" lorsque le terminal te demande `Database File Path: (/var/www/directus.site/data.db)` pour utiliser le chemin par défaut pour le fichier de la base de données.
> 
> **Si tu utilises MySQL/MariaDB :**
> - _Sélectionne `MySQL / MariaDB / Aurora` avec les flèches de ton clavier, puis valide ton choix avec `Entrée`._
> - `Database Host: (127.0.0.1)` - _Appuie simplement sur la touche `Entrée` pour valider `127.0.0.1` comme adresse de ton SGBDR._
> - `Port: (3306)` - _Appuie simplement sur la touche `Entrée` pour valider `3306` comme port de connexion à ton SGBDR._
> - `Database Name: (directus)` - _Entre le nom que tu as donné à la BDD que tu as créée précédemment (requête SQL `CREATE DATABASE`), puis valide avec `Entrée`._
> - `Database User:` - _Entre le nom de l'utilisateur de BDD que tu as créé précédemment (requête SQL `CREATE USER`), puis valide avec `Entrée`._
> - `Database Password:` - _Entre le mot de passe de l'utilisateur de BDD que tu as créé précédemment (requête SQL `CREATE USER`), puis valide avec `Entrée`._

> `Email (admin@example.com)`
>
> _Entre l'adresse e-mail que tu souhaites donne au compte du super admin par défaut, puis valide avec `Entrée`._

> `Password`
>
> _Entre le mot de passe que tu souhaites utliser pour le compte du super admin par défaut, puis valide avec `Entrée` **APRÈS AVOIR NOTÉ CE MOT DE PASSE EN LIEU SÛR**._

### Upload de la BDD SQLite en production

> ⚠️ **Si tu utilises MySQL/mariaDB pour la BDD de Directus, peux sauter cette section et passer directement à [Étape 3 : Préparer le serveur Directus](#étape-3--préparer-le-serveur-directus)**

🚨 _Ouvre un nouveau terminal pour exécuter cette commande ! (Tu n'as pas besoin de te connecter à ta VM serveur dans ce nouveau terminal.)_ 🚨

Dans la commande suivante :
- remplace `<chemin de data.db local>` par le chemin de ton fichier de BDD SQLite (par exemple, si tu es déjà dans le dossier de ton Directus local : `./data.db`)
- remplace `<pseudo>` par ton pseudo GitHub qui est utilisé dans l'adresse de ta VM server Kourou

```bash
scp <chemin de data.db local> student@<pseudo>-server.eddi.cloud:/var/www/directus.site/data.db
```

<details>
<summary>SPOILER: Détail de la commande</summary>

La commande `scp` (pour _SSH Copy_) permet de copier un fichier via SSH entre deux machines. Ici, on souhaite copier un fichier de ta machine (local) vers ta VM server Kourou (distant).

Cette commande s'utilise comme suit :
```
scp SOURCE DESTINATION
```
Où :
- `SOURCE` est le chemin du fichier à copier
- `DESTINATION` est le chemin du fichier résultat de la copie (si le fichier existe déjà, il sera écrasé)

Pour spécifier sur quelle machine il faut copier le fichier, on indique l'utilisateur et l'adresse de cette machine dans le chemin de `DESTINATION` :
```
<utilisateur>@<adresse>:<chemin du fichier>
student@pseudo-server.eddi.cloud:/var/www/directus.site/data.db
```

On donne le nom de l'utilisateur pour permettre à `scp` de se connecter en SSH à la machine distante (ta VM server Kourou) mais aussi pour créer le fichier avec les bons droits.
</details>

Voilà, Directus est installé ! Mais il n'est pas encore opérationnel…

## Étape 3 : Préparer le serveur Directus

### Configurer le service Directus

```bash
echo "[Unit]
Description=Directus Service
Requires=mariadb.service
After=mariadb.service
[Service]
User=student
Group=student
Restart=always
WorkingDirectory=/var/www/directus.site
Environment=NODE_VERSION=18
ExecStart=/home/student/.nvm/nvm-exec npx -y directus start
[Install]
WantedBy=multi-user.target
" | sudo tee -a /etc/systemd/system/directus.service > /dev/null
```

<details>
<summary>SPOILER: Détail de la commande</summary>

La commande `echo` permet de fournir une chaîne de texte au terminal. Ici, la chaîne en question est le contenu du fichier de configuration du service qui se chargera de lancer Directus à notre place. Grâce à la syntaxe du _pipe_ (`|`), on dit au terminal d'utiliser ce texte comme argument de la commande `tee` qui permet, avec l'option `-a` d'ajouter le contenu dans le fichier dont on lui donne le chemin (ici `/etc/systemd/system/directus.service`).

En résumé, on crée ce fichier et on lui injecte la configuration d'un service Linux comme contenu. On doit exécuter la commande `tee` avec les droits administrateurs (`sudo`) car on touche ici aux fichiers du système. La redirection de l'affichage du terminal vers `> /dev/null` sert juste à ne pas afficher le retour de la commande `tee` qui répèterait juste le texte mis dans le fichier.
</details>

<details>
<summary>SPOILER: Détail du service</summary>

Le contenu du fichier `/etc/systemd/system/directus.service` est la configuration d'un service Linux. Un service, c'est un programme que le système d'exploitation va pouvoir lancer en arrière-plan de manière automatisée, et relancer en cas de souci. Par exemple, quand tu as installé le serveur Apache, un service `apache2` a été créé ; ici, c'est un service `directus` qu'on est en train de créer nous-mêmes !

```
[Unit]
Description=Directus Service
Requires=mariadb.service
After=mariadb.service
```

Cette première section permet de décrire un peu ton service. On y indique une brève description, mais surtout on précise qu'il a besoin que le service `mariadb` (ton serveur de bases de données) doit être en train de tourner - ben oui, essaie d'utiliser Directus sans base de données, pour voir ? 😆

```
[Service]
User=student
Group=student
Restart=always
WorkingDirectory=/var/www/directus.site
Environment=NODE_VERSION=18
ExecStart=/home/student/.nvm/nvm-exec npx -y directus start
```

Dans la section `Service`, on décrit dans quelles conditions le système doit lancer le service et comment il doit le faire :
- `User` définit que le système doit exécuter le programme du service en tant que l'utilisateur `student` (c'est toi 😉)
- `Group` indique que le groupe utilisé pour l'exécution du service est aussi le groupe `student` ; en combinaison avec `User`, cette directive assure notamment que l'utilisateur `student` aura tous les droits nécessaires pour manipuler les fichiers enregistrés dans le répertoire `/var/www/directus.site`
- `Restart` permet de dire au système comment réagir dans le cas où le programme du service s'arrêterait de lui-même (ce qui peut arriver en cas d'erreur, par exemple) ; ici, `always` indique que le système doit obligatoirement tenter de redémarrer le programme dès qu'il s'arrête tout seul
- `WorkingDirectory` spécifie le répertoire à partir duquel lancer le programme du service ; dans ton cas, il faut que ce soit le répertoire de l'installation de Directus pour que le serveur de Directus puisse bien démarrer
- `Environment` sert à donner des informations additionnelles à la commande qui lancera le programme du service ; ici, va forcer l'utilisation de la version 18 de Node.js car Directus ne supporte pas de version plus récente
- `ExecStart` donne la commande que le système doit exécuter pour lancer le programme du service ; on utilise ici l'exécutable `nvm-exec` fourni avec Node Version Manager pour s'assurer que le serveur Directus sera lancé avec la version de Node.js donnée par la variable d'environnement `NODE_VERSION` donnée dans la directive `Environment` ; la commande qui lance le serveur, elle, tu la connais, c'est `npx directus start` (avec l'option `-y` qui permet d'installer automatiquement le module `directus` s'il ne l'est pas encore)

```
[Install]
WantedBy=multi-user.target
```

Enfin, cette dernière section permet d'indiquer au système à quel moment il doit démarrer le service dans le cadre d'une exécution automatique. La valeur `multi-user.target` fera en sorte que le service sera lancé dès que les utilisateurs pourront s'identifier dans le système via un terminal - en gros, dès que le système sera prêt.
</details>

### Démarrer le service

```bash
sudo systemctl start directus.service
```

<details>
<summary>SPOILER: Détail de la commande</summary>

La commande `systemctl` permet d'interagir avec les service Linux. La sous-commande `start`, elle, sert à démarrer un service (ici `directus`).

Comme on agit sur l'exécution de programmes au niveau du système, on exécute cette commande avec les droits administrateur (`sudo`).

> ℹ️ **Interagir avec ton service**
>
> À tout moment tu peux vérifier si ton service est en cours d'exécution avec cette commande (pas besoin des droits administrateur) :
>
> ```bash
> systemctl status directus.service
> ```
>
> Tu peux aussi consulter les logs du service avec cette commande :
>
> ```bash
> journalctl -u directus.service
> ```
>
> Tu peux aussi arrêter le service ainsi (droits administrateur requis) :
>
> ```bash
> sudo systemctl stop directus.service
> ```

</details>

### Activer le démarrage automatique du service

```bash
sudo systemctl enable directus.service
```

<details>
<summary>SPOILER: Détail de la commande</summary>

La sous-commande `enable` de `systemctl` permet de dire au système d'exploitation de lancer un service au démarrage. Ici, à partir du prochain démarrage ou redémarrage de ta VM Server, le service `directus` sera lancé automatiquement et tu n'auras pas à le lancer toi-même.

> ℹ️ Tu peux dire au système d'arrêter de lancer ton service au démarrage avec la commande suivante :
>
> ```bash
> sudo systemctl disable directus.service
> ```

</details>

⚠️ Le lancement automatique du service ne prendra effet qu'à partir du prochain démarrage du système, tu dois donc lancer toi-même ton service avec `susdo systemctl start directus.service` pour cette fois.

## Étape 4 : Configurer le proxy Apache

### Utilisation des ports 8080 et 8443

```bash
sudo sed -i 's#Listen 80#Listen 80\n\tListen 8080#g' /etc/apache2/ports.conf
```

```bash
sudo sed -i 's#Listen 443#Listen 443\n\tListen 8443#g' /etc/apache2/ports.conf
```

<details>
<summary>SPOILER: Détail des commandes</summary>

J'avoue, ces deux commandes sont carrément barbares 🧌 et, entre nous, franchement pas jolies 🫠

Par défaut, le serveur Apache utilise le port 80 pour recevoir les requêtes HTTP, et le port 443 pour les requêtes en HTTPS. Ces deux ports sont les ports standard du protocole HTTP.

Sauf que toi, tu dois faire en sorte que Directus et son API soient accessibles sur les ports 8080 en HTTP et 8443 en HTTPS pour ne pas entrer en conflit avec ton front-end qui utilisera très sûrement les ports 80 et 443.

Ces deux commandes viennent simplement ajouter les ports 8080 et 8443 dans la liste des ports qui doivent être écoutés par Apache.
</details>

### Activation du module proxy d'Apache

```bash
sudo a2enmod proxy proxy_http
```

<details>
<summary>SPOILER: Détail de la commande</summary>

Par défaut, Apache ne sait pas comment se comporter en tant que serveur proxy. Il faut donc activer certains de ses modules avec la commande `a2enmod` (avec les droits administrateurs) pour le lui permettre :
- Le module `proxy` lui permet de reconnaître les directives de configuration qui décrivent un comportement en tant que proxy
- Le module `proxy_http` permet à Apache de se comporter comme serveur proxy en dialoguant avec le serveur final via le protocole HTTP (ça tombe bien, le serveur Directus "parle" HTTP 😉)
</details>

### Définition du virtual host pour le proxy

```bash
echo "<VirtualHost *:8080>
    ProxyPreserveHost On
    ProxyPass / http://localhost:8055/
    ProxyPassReverse / http://localhost:8055/
</VirtualHost>
" | sudo tee /etc/apache2/sites-available/directus.conf
```

<details>
<summary>SPOILER: Détail de la commande</summary>

Afin qu'Apache nous donne accès à Directus via le port 8080, il est nécessaire de lui spécifier qu'il doit se comporter comme proxy **uniquement** lorsqu'il reçoit une requête sur ce port.

Pour ce faire, on configure un _virutal host_, c'est-à-dire un "site", pour indiquer à Apache comment il doit se comporter dans un cas précis :
- `<VirtualHost *:8080>` indique le début d'une section de configuration de type "virtual host" ; Apache devra appliquer les directives de configuration de cette section à chaque fois qu'il recevra une requête HTTP sur le port 8080
- Les directives `ProxyPreserveHost`, `ProxyPass` et `ProxyPassReverse` servent à configurer le proxy en indiquant que les requêtes HTTP doivent être "transmisent" au serveur HTTP qui tourne à l'adresse `http://localhost:8055/` (c'est ton serveur Directus ! 😄)
</details>

### Activation du virtual host

```bash
sudo a2ensite directus.conf
```

<details>
<summary>SPOILER: Détail de la commande</summary>

De la même manière que la commande `a2enmod` est un utilitaire pour activer des modules d'Apache, `a2ensite` permet d'activer des "sites" (virtual hosts). Ici, tu actives le virtual host que tu viens de créer pour ton installation de Directus.
</details>

### Redémarrage du serveur Apache

Redémarre enfin le serveur Apache pour qu'il prenne en compte toutes tes modifications :

```bash
sudo systemctl restart apache2
```

## Étape 5 : Contempler le fruit de son travail 🤩

Voilà, tu dois normalement pouvoir accéder à ton Directus déployé sur `http://<pseudo>-server.eddi.cloud:8080`, pour toi et pour le reste du monde !

Alors, content·e ?

Directus se lancera sur ta VM à chaque fois que tu la démarreras ou redémarreras. Pense à mettre à jour l'URL dans ton front-end, surtout en production !

## Étape 6 (bonus) : Installer Adminer pour administrer la BDD

```bash
sudo apt update
sudo apt install -y adminer
sudo a2enconf adminer
sudo systemctl restart apache2
```

Adminer est accessible sur `http://<pseudo>-server.eddi.cloud/adminer` et tu peux te connecter à la base de données de Directus avec l'utilisateur que tu as créé plus haut dans ce tutoriel (et son mot de passe 😉) ! 🎉

## Étape 7 (bonus) : Mettre en place HTTPS pour Directus

⚠️ Cette section part du principe que tu as déjà suivi le tutoriel pour [passer une application en HTTPS](./deploy-https.md) et que tu as fait générer à Certbot la configuration Apache pour le virtual host par défaut (fichiers `000-default.conf` et `000-default-le-ssl.conf`).

### Configuration du virtual host en HTTPS

⚠️ Dans la commande suivante, **remplace bien toutes les occurrences de `<pseudo>` par ton pseudo GitHub avant de valider** !!!

```bash
echo "<VirtualHost *:8443>
    Include /etc/letsencrypt/options-ssl-apache.conf
    SSLCertificateFile /etc/letsencrypt/live/<pseudo>-server.eddi.cloud/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/<pseudo>-oclock-server.eddi.cloud/privkey.pem

    ProxyPreserveHost On
    ProxyPass / http://localhost:8055/
    ProxyPassReverse / http://localhost:8055/
</VirtualHost>
" | sudo tee /etc/apache2/sites-available/directus-le-ssl.conf
```

### Configuration de la redirection automatique de HTTP vers HTTPS

Pour forcer la sécurité, on met souvent en place une redirection qui oblige les clients qui tente de communiquer avec le serveur en HTTP à utiliser HTTPS.

⚠️ Dans la commande suivante, **remplace bien toutes les occurrences de `<pseudo>` par ton pseudo GitHub avant de valider** !!!

```bash
echo "<VirtualHost *:8080>
    RewriteEngine on
    RewriteCond %{SERVER_NAME} =<pseudo>-oclock-server.eddi.cloud
    RewriteRule ^ https://%{SERVER_NAME}:8443%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>
" | sudo tee /etc/apache2/sites-available/directus.conf
```

### Activation du virtual host HTTPS

```bash
sudo a2ensite directus-le-ssl.conf
sudo systemctl restart apache2
```

### Terminé ! 🍾

Et voilà, tu dois normalement pouvoir accéder à Directus en HTTPS via `https://<pseudo>-server.eddi.cloud:8443` ! Et ça fonctionne aussi pour son API, bien évidemment ! 🤸

En plus, si tu te rends sur `http://<pseudo>-server.eddi.cloud:8080`, tu devrais être automatiquement redirigé vers la version en HTTPS 👌

🔒 On est pas bien, là, avec la sécurité ?