<?php

declare(strict_types=1);

namespace Blog\Models;

/**
 * Blog Post Model
 */
class Post
{
    private ?int $id;
    private string $title;
    private string $content;
    private ?string $createdAt;
    private ?string $updatedAt;
    
    /**
     * @param int|null $id Post ID
     * @param string $title Post title
     * @param string $content Post content
     * @param string|null $createdAt Creation timestamp
     * @param string|null $updatedAt Last update timestamp
     */
    public function __construct(
        ?int $id = null,
        string $title = '',
        string $content = '',
        ?string $createdAt = null,
        ?string $updatedAt = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
    
    /**
     * Get post ID
     * 
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * Set post ID
     * 
     * @param int|null $id
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Get post title
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * Set post title
     * 
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Get post content
     * 
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * Set post content
     * 
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Get post creation timestamp
     * 
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
    
    /**
     * Set post creation timestamp
     * 
     * @param string|null $createdAt
     * @return self
     */
    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    
    /**
     * Get post update timestamp
     * 
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }
    
    /**
     * Set post update timestamp
     * 
     * @param string|null $updatedAt
     * @return self
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    
    /**
     * Convert post to array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
    
    /**
     * Create post from array
     * 
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? '',
            $data['content'] ?? '',
            $data['created_at'] ?? null,
            $data['updated_at'] ?? null
        );
    }
}
