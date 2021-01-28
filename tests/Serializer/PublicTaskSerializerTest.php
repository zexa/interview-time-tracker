<?php

declare(strict_types=1);

namespace App\Tests\Serializer;

use App\Entities\PublicTask;
use App\Serializer\PublicTaskSerializer;
use PHPUnit\Framework\TestCase;

class PublicTaskSerializerTest extends TestCase
{
    /**
     * @var PublicTaskSerializer
     */
    private $publicTaskSerializer;
    /**
     * @var PublicTask
     */
    private $publicTaskObject;
    /**
     * @var string
     */
    private $publicTaskJson;

    public function setup(): void
    {
        $this->publicTaskSerializer = new PublicTaskSerializer();
        $this->publicTaskObject = new PublicTask(
            'test_title',
            'test_comment',
            new \DateTimeImmutable("2021-01-26"),
            new \DateInterval('PT10S'),
        );
        $this->publicTaskJson = <<<EOL
{"title":"test_title","comment":"test_comment","date":"2021-01-26T00:00:00+00:00","duration":"P0Y0M0DT0H0M10S"}
EOL;
    }

    public function testDeserializePublicTask()
    {
        $this->assertEquals(
            $this->publicTaskSerializer->deserializePublicTask(
                $this->publicTaskJson,
                PublicTaskSerializer::FORMAT_JSON
            ),
            $this->publicTaskObject
        );
    }

    public function testSerializePublicTask()
    {
        $this->assertEquals(
            $this->publicTaskSerializer->serializePublicTask(
                $this->publicTaskObject,
                PublicTaskSerializer::FORMAT_JSON
            ),
            $this->publicTaskJson
        );
    }
}
