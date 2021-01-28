<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\UserRequest;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserRequestSerializer extends Serializer
{
    const FORMAT_JSON = 'json';

    public function __construct(
        GetSetMethodNormalizer $getSetMethodNormalizer,
        JsonEncoder $jsonEncoder
    ) {
        parent::__construct(
            [$getSetMethodNormalizer],
            [$jsonEncoder]
        );
    }

    /**
     * @param string $userRequest
     * @param string $format
     * @return UserRequest
     */
    public function deserializeUserRequest(string $userRequest, string $format): UserRequest
    {
        return $this->deserialize($userRequest, UserRequest::class, $format);
    }

    /**
     * @param UserRequest $userRequest
     * @param string $format
     * @return string
     */
    public function serializeUserRequest(UserRequest $userRequest, string $format): string
    {
        return $this->serialize($userRequest, $format);
    }
}
