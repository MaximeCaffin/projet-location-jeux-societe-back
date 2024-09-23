---  ETAPES  -- 


1. composer require symfony/security-bundle
2. Pour créer les utilisateurs, utiliser cette commande : php bin/console make:user 
3. Créer la route connexion/déconnexion : php bin/console make:security:form-login
4. Configurer l'entité User via https://symfony.com/doc/current/security.html
5. Migration et do/fi/lo php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixture:load // php bin/console do:fi:lo
6. Sur adminer, créer utilisateur avec son rôle déterminé comme ceci ["ROLE_ADMIN"] + choisir mdp 
7. Lancer la commande pour hâcher le mdp : php bin/console security:hash-password
8. copier le mdp hâché et coller dans BDD à la place du mdp choisi