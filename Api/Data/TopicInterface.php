<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api\Data;

interface TopicInterface
{
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;
    public const STATUS_PENDING = 2;

    public function getId(): ?int;

    public function setId(?int $id): self;

    public function getForumId(): ?int;

    public function setForumId(?int $forumId): self;

    public function getUserId(): ?int;

    public function setUserId(?int $userId): self;

    public function getTitle(): ?string;

    public function setTitle(string $title): self;

    public function getDescription(): ?string;

    public function setDescription(?string $description): self;

    public function getUrlKey(): ?string;

    public function setUrlKey(string $urlKey): self;

    public function getStatus(): int;

    public function setStatus(int $status): self;

    public function getIconId(): ?string;

    public function setIconId(?string $iconId): self;

    public function getPriority(): int;

    public function setPriority(int $priority): self;

    public function getStoreId(): ?int;

    public function setStoreId(?int $storeId): self;

    public function getMetaDescription(): ?string;

    public function setMetaDescription(?string $metaDescription): self;

    public function getMetaKeywords(): ?string;

    public function setMetaKeywords(?string $metaKeywords): self;

    public function getTotalViews(): int;

    public function setTotalViews(int $totalViews): self;

    public function getTotalPosts(): int;

    public function setTotalPosts(int $totalPosts): self;

    public function getLastPostId(): ?int;

    public function setLastPostId(?int $lastPostId): self;

    public function getCreatedAt(): ?string;

    public function setCreatedAt(string $createdAt): self;

    public function getUpdatedAt(): ?string;

    public function setUpdatedAt(string $updatedAt): self;

    public function getIsDeleted(): bool;

    public function setIsDeleted(bool $isDeleted): self;

    public function getIsSticky(): bool;

    public function setIsSticky(bool $isSticky): self;


}
