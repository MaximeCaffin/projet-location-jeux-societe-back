### Creation de la base de donnée

Pour mettre en place une base de donnée il faut dans un premiers temps la créer avec l'aide d'un interface (avec adminer ou phpmyadmin). Il faudra juste lui créer son nom

### Creation de notre environement

Dans la partie back vous pouver voir (tous en bas) un fichier s'appellant .env.production (ou local). Si il n'y est pas il faut la créer. 

Ce fichier contient une ligne comme ceci :
```
DATABASE_URL="mysql://root:root@127.0.0.1:3306/playpal?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
```
Le **root:root** défini l'utilisateur et son mot de pass
Ensuite le **playpal** est le nom de la base de donnée 

C'est ce fichier qui permet de connecter notre code a notre BDD du coup vérifier bien les données rentrer 

### Creation de nos table 

Maintenant pour créer nos tables que l'on appelle ici les entités et configuer nos champs, nous allons utiliser la commande dans notre terminal
```
php bin/console make:entity
```
Une fois ça fait, la console vous nous poser different question et a la fin notre entités sera créer :

1.
```
Class name of the entity to create or update:
> Product
```
Ici on choisi le nom de notre entités

2.
```
New property name (press <return> to stop adding fields):
> name
```
On choisi le nom de notre champ

3.
```
Field type (enter ? to see all types) [string]:
> string
```
Son type (string, init, date)

4.
```
Field length [255]:
> 255
```
Dans notre exemple c'est un string du coup nous avons d'autre question pour configurer notre champ

5.
```
Can this field be null in the database (nullable) (yes/no) [no]:
> no
```
On nous demande si notre champ peut etre null

Voila nous avons créer dans notre entité product et le champ name. Après on continuer les étapes de 2 à 5 autant de fois que l'on veut. Quand nous avons finis on appuye sur ENTER sans rentrer de nom

et si

On veut rajouter un champ de notre entités créer, il suffit de rentrer la commander 
```
php bin/console make:entity
```
avec le même nom que notre entités puis de rajouter ce que l'on veut 

### Mettre en relation 

Pour faire une relation entre deux Entites il faut déja en créer une (voir plus haut). Nous allons créer une deuxième entités et sur l'un de nos champs nous allons indiquer que son type est relation. Alors d'autres question nous serons poser afin de créer une relation

Voici un exemple :

```
$ php bin/console make:entity

Class name of the entity to create or update (e.g. BraveChef):
> Product

New property name (press <return> to stop adding fields):
> category

Field type (enter ? to see all types) [string]:
> relation

What class should this entity be related to?:
> Category

Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
> ManyToOne

Is the Product.category property allowed to be null (nullable)? (yes/no) [yes]:
> no

Do you want to add a new property to Category so that you can access/update
Product objects from it - e.g. $category->getProducts()? (yes/no) [yes]:
> yes

New field name inside Category [products]:
> products

Do you want to automatically delete orphaned App\Entity\Product objects
(orphanRemoval)? (yes/no) [no]:
> no

New property name (press <return> to stop adding fields):
>
(press enter again to finish)
```

### Migration

Une fois que nous avons créer toutes nos Entités que l'on souhaite. Nous les migrons vers notre BDD et ainsi notre base de données sera prête. 

Pour cela il suffit d'entre les commandes

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```