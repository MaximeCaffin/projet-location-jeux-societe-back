<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\BoardGameProvider;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private $categories = [];

    public function load(ObjectManager $manager): void
    {
        foreach (BoardGameProvider::$boardGameCategories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->categories[] = $category;
        }

        $manager->flush();
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}
