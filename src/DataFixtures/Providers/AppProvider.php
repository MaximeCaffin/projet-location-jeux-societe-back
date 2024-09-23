<?php

namespace App\DataFixtures\Providers;

use Faker\Provider\Base;

class BoardGameProvider extends Base
{
    public static $boardGames = [
        'Les Colons de Catane' => 13,
        'Les Aventuriers du Rail' => 9209,
        'Pandémie' => 30549,
        'Carcassonne' => 836,
        'Total Domination' => 36218,
        '7 Wonders' => 68448,
        'Splendor' => 176896,
        'Codenames' => 198773,
        'Azul' => 230802,
        'Gloomhaven' => 174430,
        'Wingspan' => 266192,
        'Terraforming Mars' => 167791,
        'Root' => 237182,
        'Scythe' => 169786,
        'The Resistance' => 128210,
        'Dixit' => 39856,
        'Sheriff de Nottingham' => 157969,
        'Blood Rage' => 170216,
        'Betrayal at House on the Hill' => 10547,
        'Horreur à Arkham' => 15987,
        'Les Contrées de l\'Horreur' => 126163,
        'Twilight Struggle Deluxe' => 3659,
        'King of Tokyo Horreur Edition' => 70323,
        'God of War' => 31260,
        'Rex' => 3076,
        'Mage Knight' => 96848,
        'Power Rangers: Heroes of the Grid' => 2651,
        'SMALL WORLD OF WARCRAFT' => 40692,
        'L\'Âge de Pierre' => 34635,
        'Les Châteaux de Bourgogne Deluxe' => 84268,
        'Clank!' => 201808,
    ];

    public static $boardGameCategories = [
        'jeux de société',
        'jeu apéro',
        'jeux de cartes',
        'jeux familial/enfants',
        'jeu narratif',
        'jeu historique',
        'jeu classique'
    ];

    public function boardGameTitle(): array
{
    $game = self::$boardGames[array_rand(self::$boardGames)];
    return [$game, array_search($game, self::$boardGames)];
}

    public function boardGameCategory(): string
    {
        return self::$boardGameCategories[array_rand(self::$boardGameCategories)];
    }
}