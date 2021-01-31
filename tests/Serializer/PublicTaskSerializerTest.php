<?php

declare(strict_types=1);

namespace App\Tests\Serializer;

use App\Entity\PublicTask;
use App\Serializer\PublicTaskSerializer;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateIntervalNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class PublicTaskSerializerTest extends TestCase
{
    private PublicTaskSerializer $publicTaskSerializer;
    private PublicTask $publicTaskObject;
    private string $publicTaskJson;

    public function setup(): void
    {
        $this->publicTaskSerializer = new PublicTaskSerializer(
            new DateTimeNormalizer(),
            new DateIntervalNormalizer(),
            new GetSetMethodNormalizer(),
            new JsonEncoder(),
            new CsvEncoder()
        );
        $this->publicTaskObject = new PublicTask(
            'test_title',
            ['test_comment'],
            new DateTimeImmutable("2021-01-26"),
            new DateInterval('PT10S'),
            null
        );
        $this->publicTaskJson = <<<EOL
{"title":"test_title","comments":["test_comment"],"date":"2021-01-26T00:00:00+00:00","duration":"P0Y0M0DT0H0M10S","hash":null}
EOL;
    }

    public function testDeserializePublicTask()
    {
        $this->assertEquals(
            $this->publicTaskObject,
            $this->publicTaskSerializer->deserializePublicTask(
                $this->publicTaskJson,
                PublicTaskSerializer::FORMAT_JSON
            )
        );
    }

    public function testSerializePublicTask()
    {
        $this->assertEquals(
            $this->publicTaskJson,
            $this->publicTaskSerializer->serializePublicTask(
                $this->publicTaskObject,
                PublicTaskSerializer::FORMAT_JSON
            )
        );
    }
}
