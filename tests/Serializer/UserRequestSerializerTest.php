<?php

namespace App\Tests\Serializer;

use App\Entity\UserRequest;
use App\Serializer\UserRequestSerializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class UserRequestSerializerTest extends TestCase
{
    /**
     * @var UserRequestSerializer
     */
    private $userRequestSerializer;
    /**
     * @var UserRequest
     */
    private $userRequest;
    /**
     * @var string
     */
    private $userRequestJson;

    public function setup(): void
    {
        $this->userRequestSerializer = new UserRequestSerializer(
            new GetSetMethodNormalizer(),
            new JsonEncoder()
        );
        $this->userRequest = new UserRequest(
            'user@example.com',
            'pass'
        );
        $this->userRequestJson = <<<EOL
{"email":"user@example.com","password":"pass"}
EOL;
    }

    public function testDeserializeUserRequest(): void
    {
        $this->assertEquals(
            $this->userRequest,
            $this->userRequestSerializer->deserializeUserRequest(
                $this->userRequestJson,
                UserRequestSerializer::FORMAT_JSON
            )
        );
    }

    public function testSerializeUserRequest(): void
    {
        $this->assertEquals(
            $this->userRequestJson,
            $this->userRequestSerializer->serializeUserRequest(
                $this->userRequest,
                UserRequestSerializer::FORMAT_JSON
            )
        );
    }
}
