<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api\Data;

interface ModeratorInterface
{
    public function getId(): ?int;

    public function setId(?int $id): self;

    public function getUserId(): ?int;

    public function setUserId(int $userId): self;

    public function getWebsiteId(): ?int;

    public function setWebsiteId(?int $websiteId): self;
}
