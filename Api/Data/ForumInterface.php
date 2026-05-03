<?php
declare(strict_types=1);

namespace Ronald2Wing\Forum\Api\Data;

interface ForumInterface
{
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    public function getId(): ?int;

    public function setId(?int $id): self;

    public function getParentId(): ?int;

    public function setParentId(?int $parentId): self;

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

    public function getTotalTopics(): int;

    public function setTotalTopics(int $totalTopics): self;

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
    public function getCustomerGroups(): ?array;

    public function setCustomerGroups(array $groups): self;

}
