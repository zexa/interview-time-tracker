<?php

declare(strict_types=1);

namespace App\Generator;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidGenerator
{
    public function generate(): UuidInterface
    {
        return Uuid::uuid4();
    }

    public function generateString(): string
    {
        return $this->generate()->toString();
    }
}
