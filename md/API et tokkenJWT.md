### --01 findAll--

Pour cette première étape on va voir comment  récupérer les infos de la base de données et l'afficher côté front grace au Json (language qui permet de communiquer entre les technos)

Pour cela on va créer notre contrôleur que l'on range dans un dossier API. (ps : coter symfo nous avons un commande dans le terminal "php bin/console make:controller" qui nous permet de créer notre controlleur)


1.
```
#[Route('api/category', name: 'app_api_category')]
```
Voilà notre route générale qui va s'appliquer sur toutes nos prochaines routes. Elle se place avant notre classe. Elle est composé d'une URL pour le navigateur et d'un nom pour appeler dans notre code


2.
```
 #[Route('/', name: 'browse', methods:"GET")]
```
Puis on fait une route pour chaque fonction que l'on veut faire. la route URL et le nom vont se rajouter à la route principale et l'on rajoute ici une méthode. (GET est la méthode pour afficher des infos)


3.
```
public function browse(CategoryRepository $categoryRepo): JsonResponse
```
Ensuite nous créons notre fonction qui va contenir notre code.

**CategoryRepository** va nous permettre d'exécuter des requêtes préparer dans notre entité (ou table) category

Et l'on indique **JsonResponse** pour précise que notre requête est en Json


4.
```
$categoryAllList = $categoryRepo -> findAll();
```
Cette ligne de code nous permet d'exécuter la requête findAll() dans category pour remonter toutes les informations des catégories


5.
```
return $this->json($categoryAllList,  Response::HTTP_OK, [], ['groups' => 'game_browse']);
```
Pour finir on retourne le résulta en json.

**Response::HTTP_OK** renvoie la réponse correspondant à notre requête pour dire que tout est bon

**[]** on doit toujours renvoyer un tableau vide

**['groups' => 'game_browse']** Pour renvoyer les infos que l'on veut retourner on créé des groupes dans nos entités et après il suffit de les appelés.

(Exemple de groupe dans les Entités  :

```
   #[ORM\Column(length: 255)]
   #[Groups(['game_browse', 'category_browse','rent_browse'])]
   private ?string $Name = null;
```

)





### ---02 find--

En reprenant notre exemple plus haut, on va maintenant trouver les informations d'une catégorie bien précise en utilisant sont ID


1.
```
#[Route('/{id<\d+>}', name: 'read', methods:"GET")]
```

on crée notre route en indiquant qu'il y aura une ID dans l'URL (Elle est notée en reg ex pour que l'URL comprend) et toujours en méthode GET


2.
```
public function read(int $id, CategoryRepository $categoryRepo): JsonResponse
```

dans notre fonction par rapport à un findall on rajoute $id et int


3.
```
 $categoryGameList = $categoryRepo -> find($id);
```

On exécute la fonction find et l'on utilise l'id des catégories  pour trouvé celle que l'on veut


4.
```
if (is_null($categoryGameList))
        {
            return $this->categoryNotFound();
        }

    private function categoryNotFound()
    {

    $data = [
            "success" => false,
            "message" => "category not found"
        ];

        return $this->json($data, Response::HTTP_NOT_FOUND)
    }
```

on rajoute une condition pour retourner une fonction qui va afficher un message si notre requête ne trouve pas l'ID


5.
```
return $this->json($categoryGameList,  Response::HTTP_OK, [], ['groups' => 'category_browse'])
```

Puis comme pour le findall on retourne nos infos demander et notre requête qui indique que tout est Ok.





### ---03 Tokken JWT--

1. Tous d'abord nous allons intaller notre Tokken (**Attention c'est l'installation coté symfo**) :

Pour commencer lancer la commande "php composer.phar require "lexik/jwt-authentication-bundle"" pour installer le tokken dans votre code

2. Ensuite on génére notre clé avec la commande "php bin/console lexik:jwt:generate-keypair" **Attention il faudra générer toujours générer la clé lorsque vous parter d'un nouveau git clone**
3. copier ce code :
SerializerInterface
```
 login:
            pattern: ^/api/login
            stateless: true
            json_login:
                check_path: /api/login_check
                success_handler: lexik_jwt_authentication.handler.authentication_success
                failure_handler: lexik_jwt_authentication.handler.authentication_failure

        api:
            pattern:   ^/api
            stateless: true
            jwt: ~
```

et coller ce code dans **security.yaml** dans **firewalls**





### ---  ETAPE 04 Add--

Maintenant on va voir comment on prend des données envoyer du coté front pour le rajouter a notre BDD. Nous allons prendre en exemple les favories

1.
```
#[Route('/add', name: 'add', methods:"POST")]
```
On peut noter que la méthods est different (POST) car la nous voulons agir sur le BDD

2.
```
public function add(Request $request, EntityManagerInterface $em, SerializerInterface $serializer)
```
Dans notre fonctions nous avons les paramètres suivant :

**Request** : Objet représentant la requête HTTP entrante.

**EntityManagerInterface** : pour interagir avec la base de données.

**SerializerInterface** : pour désérialiser les données JSON (on va ça plus bas).

3.
```
$json = $request->getContent();
$favorite = $serializer->deserialize($json, ContentFavorite::class, 'json');
```
On récupére le contenue de la requete et nous la désérialition pour que nous puissons manipuler les données.(Nous sommes obliger de passer par cette étape comme nous utilisons du Json)

4.
```
$user = $this->getUser();
if (!$user instanceof User) {
    throw $this->createAccessDeniedException('Utilisateur non authentifié');
}
```
**$this->getUser()** permet de récuperer les information de l'utilisateur connecté (grace au tokken JWT) et l'on rajoute un condition au cas ou la personne n'est pas connecter pour lui interdire de rajouter un favorie.

5.
```
$favorite->setUser($user);
```
Ici on défini l'utilisateur (que l'on a récupérer plus haut) dans notre favori (ou notre l'objet "ContentFavorite" pour être précis)

6.
```
$game = $favorite->getGame();
if (!$game) {
    throw $this->createNotFoundException('Le jeu demandé n\'existe pas');
}
```
Ici on défini le jeu (que l'on a récupérer dans notre requete) dans notre favori et on rajoute une condtition pour être sur que le jeu existe

7.
```
$favoriteRepository = $em->getRepository(ContentFavorite::class);
$existingFavorite = $favoriteRepository->findOneBy([
    'user' => $user,
    'game' => $game
]);

if ($existingFavorite) {
    return $this->json([
        'message' => 'Ce jeu est déjà dans vos favoris',
        'favoris' => $existingFavorite->getId()
    ], 200);
}
```
Le dépôt de notre favori est récupéré, et une recherche est effectuée pour vérifier si un favori existe déjà pour l'utilisateur et le jeu donnés. Si c'est le cas, un message approprié est renvoyé avec un code de statut HTTP 200 (OK).

8.
```
$em->persist($favorite);
$em->flush();

return $this->json([
    'message' => 'Le favoris a été créée avec succès',
    'favoris' => $favorite->getId()
], 201);
```

Si toutes nos condtions sont Ok on le rajoute a notre base de données et on renvoie une message de succés avec l'id du nouveau favorie crée.

### ---  ETAPE 05 Delete--

Maintenant on va voir comment effacer une donnée de la BDD en partant d'une requete venant du front. Nous allons prendre en exemple les favories

1.
```
 #[Route('/delete', name: 'delete', methods:"DELETE")]
```
On peut noter que la méthods est different (DELETE) car la nous voulons supprimer une donnée dans la BDD

2.
```
public function delete(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
```
Dans notre fonctions nous avons les paramètres suivant :

**Request** : Objet représentant la requête HTTP entrante.

**EntityManagerInterface** : pour interagir avec la base de données.

**SerializerInterface** : pour désérialiser les données JSON (on va ça plus bas).

3.
```
$json = $request->getContent();
$favorite = $serializer->deserialize($json, ContentFavorite::class, 'json');
```
On récupére le contenue de la requete et nous la désérialition pour que nous puissons manipuler les données.(Nous sommes obliger de passer par cette étape comme nous utilisons du Json)

4.
```
$user = $this->getUser();
if (!$user instanceof User) {
    throw $this->createAccessDeniedException('Utilisateur non authentifié');
}
```
**$this->getUser()** permet de récuperer les information de l'utilisateur connecté (grace au tokken JWT) et l'on rajoute un condition au cas ou la personne n'est pas connecter pour lui interdire de rajouter un favorie.

5.
```
$favorite->setUser($user);
```
Ici on défini l'utilisateur (que l'on a récupérer plus haut) dans notre favori (ou notre l'objet "ContentFavorite" pour être précis). 

6.
```
 $game = $favorite->getGame();
      if (!$game) {
        throw $this->createNotFoundException('Le jeu demandé n\'existe pas');
      }
```
Ici on défini le jeu (que l'on a récupérer dans notre requete) dans notre favori et on rajoute une condtition pour être sur que le jeu existe

7.
```
$favoriteRepository = $em->getRepository(ContentFavorite::class);
$existingFavorite = $favoriteRepository->findOneBy([
    'user' => $user,
    'game' => $game
]);
```
Le dépôt de notre favori est récupéré, et une recherche est effectuée pour vérifier si un favori existe déjà pour l'utilisateur et le jeu donnés.

8.
```
    if ($existingFavorite) {
        $em->remove($existingFavorite);
        $em->flush();
        return $this->json([
            'success' => true,
            'message' => 'Ce jeu a été supprimé dans vos favoris',
            'favoris' => $existingFavorite->getId()
        ], 204);
    } else {
        return $this->json([
            'success' => false,
            'message' => "Ce jeu n'est pas dans vos favoris",
        ], 200);
    }
```
Si un favori existant est trouvé, il est supprimé de la base de données en utilisant **$em->remove()** et **$em->flush()**. Un message de succes est ensuiter renvoyée

Sinon on retroune un autre message indiquant que le favorie n'existe pas. 

