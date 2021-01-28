<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entities\PublicTask;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class PublicTaskSerializer extends Serializer
{
    const FORMAT_JSON = 'json';
    const FORMAT_CSV = 'csv';

    public function __construct()
    {
        parent::__construct(
            [new DateTimeNormalizer(), new DateIntervalNormalizer(), new GetSetMethodNormalizer()],
            [new JsonEncoder(), new CsvEncoder()]
        );
    }

    public function deserializePublicTask(string $publicTask, string $format): PublicTask
    {
        return $this->deserialize($publicTask, PublicTask::class, $format);
    }

    public function serializePublicTask(PublicTask $publicTask, string $format): string
    {
        return $this->serialize($publicTask, $format);
    }
}
