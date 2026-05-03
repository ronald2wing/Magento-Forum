<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api\Data;

interface ReportInterface
{
    public const STATUS_NEW = 0;
    public const STATUS_REVIEWED = 1;

    public function getId(): ?int;

    public function setId(?int $id): self;

    public function getPostId(): ?int;

    public function setPostId(int $postId): self;

    public function getUserId(): ?int;

    public function setUserId(?int $userId): self;

    public function getReason(): ?string;

    public function setReason(?string $reason): self;

    public function getStatus(): int;

    public function setStatus(int $status): self;

    public function getCreatedAt(): ?string;

    public function setCreatedAt(string $createdAt): self;
}
