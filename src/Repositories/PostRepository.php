<?php

declare(strict_types=1);

namespace Blog\Repositories;

use Blog\Database\DatabaseConnection;
use Blog\Exceptions\ApiException;
use Blog\Models\Post;
use PDO;
use PDOException;

/**
 * Repository for Post database operations
 */
class PostRepository
{
    private PDO $db;
    
    /**
     * @throws PDOException
     */
    public function __construct()
    {
        $this->db = DatabaseConnection::getConnection();
    }
    
    /**
     * Find all posts
     * 
     * @return array
     * @throws ApiException
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('
                SELECT * FROM posts 
                ORDER BY created_at DESC
            ');
            
            $posts = [];
            while ($row = $stmt->fetch()) {
                $posts[] = Post::fromArray($row)->toArray();
            }
            
            return $posts;
        } catch (PDOException $e) {
            throw new ApiException('Failed to fetch posts: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Find posts by search term
     * 
     * @param string $searchTerm
     * @return array
     * @throws ApiException
     */
    public function findBySearchTerm(string $searchTerm): array
    {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM posts 
                WHERE title LIKE :search OR content LIKE :search
                ORDER BY created_at DESC
            ');
            
            $searchParam = '%' . $searchTerm . '%';
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
            $stmt->execute();
            
            $posts = [];
            while ($row = $stmt->fetch()) {
                $posts[] = Post::fromArray($row)->toArray();
            }
            
            return $posts;
        } catch (PDOException $e) {
            throw new ApiException('Failed to search posts: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Find post by ID
     * 
     * @param int $id
     * @return Post
     * @throws ApiException
     */
    public function findById(int $id): Post
    {
        try {
            $stmt = $this->db->prepare('
                SELECT * FROM posts 
                WHERE id = :id
            ');
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch();
            
            if (!$row) {
                throw new ApiException('Post not found', 404);
            }
            
            return Post::fromArray($row);
        } catch (PDOException $e) {
            throw new ApiException('Failed to fetch post: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Create a new post
     * 
     * @param Post $post
     * @return Post
     * @throws ApiException
     */
    public function create(Post $post): Post
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO posts (title, content, created_at, updated_at) 
                VALUES (:title, :content, NOW(), NOW())
            ');
            
            $title = $post->getTitle();
            $content = $post->getContent();
            
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->execute();
            
            $lastId = (int) $this->db->lastInsertId();
            
            return $this->findById($lastId);
        } catch (PDOException $e) {
            throw new ApiException('Failed to create post: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Update an existing post
     * 
     * @param int $id
     * @param Post $post
     * @return Post
     * @throws ApiException
     */
    public function update(int $id, Post $post): Post
    {
        try {
            // First check if the post exists
            $this->findById($id);
            
            $stmt = $this->db->prepare('
                UPDATE posts 
                SET title = :title, 
                    content = :content, 
                    updated_at = NOW() 
                WHERE id = :id
            ');
            
            $title = $post->getTitle();
            $content = $post->getContent();
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->execute();
            
            return $this->findById($id);
        } catch (ApiException $e) {
            // Re-throw ApiExceptions (like 404)
            throw $e;
        } catch (PDOException $e) {
            throw new ApiException('Failed to update post: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Delete a post
     * 
     * @param int $id
     * @return bool
     * @throws ApiException
     */
    public function delete(int $id): bool
    {
        try {
            // First check if the post exists
            $this->findById($id);
            
            $stmt = $this->db->prepare('
                DELETE FROM posts 
                WHERE id = :id
            ');
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return true;
        } catch (ApiException $e) {
            // Re-throw ApiExceptions (like 404)
            throw $e;
        } catch (PDOException $e) {
            throw new ApiException('Failed to delete post: ' . $e->getMessage(), 500);
        }
    }
}
