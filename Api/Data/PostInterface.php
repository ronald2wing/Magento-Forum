<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api\Data;

interface PostInterface
{
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;
    public const STATUS_PENDING = 2;

    public function getId(): ?int;

    public function setId(?int $id): self;

    public function getTopicId(): ?int;

    public function setTopicId(int $topicId): self;

    public function getForumId(): ?int;

    public function setForumId(int $forumId): self;

    public function getUserId(): ?int;

    public function setUserId(?int $userId): self;

    public function getContent(): ?string;

    public function setContent(string $content): self;

    public function getContentOriginal(): ?string;

    public function setContentOriginal(?string $contentOriginal): self;

    public function getStatus(): int;

    public function setStatus(int $status): self;

    public function getCreatedAt(): ?string;

    public function setCreatedAt(string $createdAt): self;

    public function getUpdatedAt(): ?string;

    public function setUpdatedAt(string $updatedAt): self;

    public function getIsDeleted(): bool;

    public function setIsDeleted(bool $isDeleted): self;

    public function getIsSticky(): bool;

    public function setIsSticky(bool $isSticky): self;


}
