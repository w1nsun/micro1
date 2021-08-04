<?php

declare(strict_types=1);

namespace App\Application\Shared\Serializer;

use Symfony\Component\Serializer\Encoder\ContextAwareDecoderInterface;
use Symfony\Component\Serializer\Encoder\ContextAwareEncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiSerializer implements SerializerInterface, ContextAwareNormalizerInterface, ContextAwareDenormalizerInterface, ContextAwareEncoderInterface, ContextAwareDecoderInterface
{
    private SerializerInterface $serializer;
    private DenormalizerInterface $denormalizer;
    private NormalizerInterface $normalizer;
    private EncoderInterface $encoder;
    private DecoderInterface $decoder;

    public function __construct(
        SerializerInterface $serializer,
        DenormalizerInterface $denormalizer,
        NormalizerInterface $normalizer,
        EncoderInterface $encoder,
        DecoderInterface $decoder
    ) {
        $this->serializer = $serializer;
        $this->denormalizer = $denormalizer;
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $format = $format ?: 'json';
        $context = array_merge(['disable_type_enforcement' => true], $context);

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        if ($this->normalizer instanceof ContextAwareNormalizerInterface) {
            return $this->normalizer->supportsNormalization($data, $format, $context);
        }

        return $this->normalizer->supportsNormalization($data, $format);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        if ($this->denormalizer instanceof ContextAwareDenormalizerInterface) {
            return $this->denormalizer->supportsDenormalization($data, $type, $format, $context);
        }

        return $this->denormalizer->supportsDenormalization($data, $type, $format);
    }

    /**
     * {@inheritdoc}
     */
    final public function encode($data, string $format, array $context = []): string
    {
        return $this->encoder->encode($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    final public function decode(string $data, string $format, array $context = [])
    {
        return $this->decoder->decode($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding(string $format, array $context = []): bool
    {
        if ($this->encoder instanceof Serializer) {
            return $this->encoder->supportsEncoding($format, $context);
        }

        return $this->encoder->supportsEncoding($format);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding(string $format, array $context = []): bool
    {
        if ($this->decoder instanceof ContextAwareDecoderInterface) {
            return $this->decoder->supportsDecoding($format, $context);
        }

        return $this->decoder->supportsDecoding($format);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($data, string $format = 'json', array $context = []): string
    {
        $context = array_merge([ObjectNormalizer::PRESERVE_EMPTY_OBJECTS => true], $context);

        return $this->serializer->serialize($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($data, string $type, string $format = 'json', array $context = [])
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
