<?php
// src/Serializer/TopicNormalizer.php
namespace App\Serializer;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EntityDenormalizer implements DenormalizerInterface
{
    
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return $this->em->find($type, $data);
    }


    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return strpos($type, 'App\\Entity\\') === 0 && (is_numeric($data) || is_string($data));

    }


    public function getSupportedTypes(?string $format): array
    {
        return 
        [
            '*' => false,
        ];
    }
}