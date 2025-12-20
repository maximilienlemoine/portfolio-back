<?php

namespace App\Serializer;

use App\Entity\Me;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class MeNormalizer implements NormalizerInterface
{
    public function __construct(
        private readonly ObjectNormalizer $objectNormalizer,
        private readonly string $uploadPath
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->objectNormalizer->normalize($object, $format, $context);

        if ($object->getCv()) {
            $data['cv'] = sprintf('%s/%s', $this->uploadPath, $object->getCv());
        }

        if ($object->getImage()) {
            $data['image'] = sprintf('%s/%s', $this->uploadPath, $object->getImage());
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Me;
    }
}