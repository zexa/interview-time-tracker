<?php

declare(strict_types=1);

namespace App\Entity;

class GetTasksParameters
{
    private int $page;
    private int $pageSize;
    private User $user;

    public function __construct(
        User $user,
        string $page,
        string $pageSize
    ) {
        $this->user = $user;
        $this->page = (int)$page;
        $this->pageSize = (int)$pageSize;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
