<?php

namespace App\Serializer;

use App\Entity\Stack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class StackNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly ObjectNormalizer $objectNormalizer,
        private readonly string $uploadPath
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->objectNormalizer->normalize($object, $format, $context);

        if ($object->getIcon()) {
            $data['icon'] = sprintf('%s/%s', $this->uploadPath, $object->getIcon());
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Stack;
    }
}