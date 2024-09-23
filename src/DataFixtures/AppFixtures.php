<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\BoardGameProvider;
use App\Entity\ContentRent;
use App\Entity\Game;
use App\Entity\Rent;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

class AppFixtures extends AbstractFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;    }


    public function load(ObjectManager $manager): void
    {
        // Faker initialisation
        $faker = Factory::create("fr_FR");
        $faker->addProvider(new BoardGameProvider($faker));
        $faker->seed(12345);

        // Get categories from CategoryFixtures
        $categoryFixture = new CategoryFixtures();
        $categoryFixture->load($manager);
        $categories = $categoryFixture->getCategories();

        // Users creation
        $userData = [
            "admin" => 'ROLE_ADMIN',
            "manager" => 'ROLE_MANAGER',
            "user" => 'ROLE_USER',
        ];

        $users = [];
        foreach ($userData as $username => $role) {
            $user = new User();
            $user->setEmail("{$username}@gmail.com");
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $password = $username;
            $user->setRoles([$role]);
            $hashedPassword = $this->hasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $users[] = $user;
        }

        // ! GAME
        $games = [];
        $gameIds = []; // Stock ID in Array

        foreach (BoardGameProvider::$boardGames as $gameName => $gameId) {
            // Check if ID is not use
            if (in_array($gameId, $gameIds)) {
                continue; // Pass to next game
            }

            $gameIds[] = $gameId; // Add ID in Array

            $imagePath = $gameId;

            $quantity = $faker->numberBetween(0, 100);
            $status = ($quantity > 0) ? 'Disponible' : 'Hors de stock';

            $game = (new Game())
                ->setName($gameName)
                ->setDescription($faker->text())
                ->setPrice($faker->randomFloat(2, 10, 100) . ' €')
                ->setCategory($categories[array_rand($categories)])
                ->setStatus($status)
                ->setQuantity($quantity)
                ->setImage($imagePath);

            $manager->persist($game);
            $games[] = $game;
        }

        // ! RENT
        $rents = [];
        for ($i = 1; $i <= 20; $i++) {
            $rent = new Rent();
            $rent->setUser($users[array_rand($users)]);
            $rent->setDateDebut($faker->dateTimeBetween('-1 month', 'now'));
            $rent->setDateFin($faker->dateTimeBetween('now', '+1 month'));
            $rent->setStatus($faker->randomElement(['Pending', 'Approved', 'Returned']));

            // $rent->setGame($game)
            $manager->persist($rent);
            $rents[] = $rent;
        }

        // ! CONTENT RENT
        foreach ($rents as $rent) {
            $nbGames = $faker->numberBetween(1, 5);
            $selectedGames = $faker->randomElements($games, $nbGames);

            foreach ($selectedGames as $game) {
                $contentRent = new ContentRent();
                $contentRent->setRent($rent);
                $contentRent->setGame($game);

                $manager->persist($contentRent);
            }
        }

        // Save in database
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
