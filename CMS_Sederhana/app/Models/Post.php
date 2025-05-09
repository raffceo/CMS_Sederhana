<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

/**
 * Post model for handling blog posts
 */
class Post extends Model
{
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public string $excerpt = '';
    public string $featured_image = '';
    public string $status = 'draft';
    public int $author_id = 0;
    public ?int $category_id = null;

    /**
     * Get the table name for the model
     * 
     * @return string
     */
    public static function tableName(): string
    {
        return 'posts';
    }

    /**
     * Save the post to the database
     * 
     * @return bool
     */
    public function save(): bool
    {
        $this->slug = $this->generateSlug($this->title);
        
        $sql = "INSERT INTO " . self::tableName() . " 
                (title, slug, content, excerpt, featured_image, status, author_id, category_id) 
                VALUES 
                (:title, :slug, :content, :excerpt, :featured_image, :status, :author_id, :category_id)";
        
        $statement = self::prepare($sql);
        $statement->bindValue(':title', $this->title);
        $statement->bindValue(':slug', $this->slug);
        $statement->bindValue(':content', $this->content);
        $statement->bindValue(':excerpt', $this->excerpt);
        $statement->bindValue(':featured_image', $this->featured_image);
        $statement->bindValue(':status', $this->status);
        $statement->bindValue(':author_id', $this->author_id);
        $statement->bindValue(':category_id', $this->category_id);

        return $statement->execute();
    }

    /**
     * Update the post in the database
     * 
     * @return bool
     */
    public function update(): bool
    {
        $this->slug = $this->generateSlug($this->title);
        
        $sql = "UPDATE " . self::tableName() . " 
                SET title = :title, 
                    slug = :slug, 
                    content = :content, 
                    excerpt = :excerpt, 
                    featured_image = :featured_image, 
                    status = :status, 
                    category_id = :category_id 
                WHERE id = :id";
        
        $statement = self::prepare($sql);
        $statement->bindValue(':title', $this->title);
        $statement->bindValue(':slug', $this->slug);
        $statement->bindValue(':content', $this->content);
        $statement->bindValue(':excerpt', $this->excerpt);
        $statement->bindValue(':featured_image', $this->featured_image);
        $statement->bindValue(':status', $this->status);
        $statement->bindValue(':category_id', $this->category_id);
        $statement->bindValue(':id', $this->id);

        return $statement->execute();
    }

    /**
     * Get the validation rules for the model
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [self::RULE_REQUIRED],
            'content' => [self::RULE_REQUIRED],
            'status' => [self::RULE_REQUIRED],
            'author_id' => [self::RULE_REQUIRED],
        ];
    }

    /**
     * Find a post by conditions
     * 
     * @param array $where The conditions to search for
     * @return Post|null
     */
    public static function findOne(array $where): ?Post
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class) ?: null;
    }

    /**
     * Get recent posts with author information
     * 
     * @param int $limit The number of posts to return
     * @return array
     */
    public static function getRecent(int $limit = 5): array
    {
        $sql = "SELECT p.*, CONCAT(u.firstname, ' ', u.lastname) as author_name 
                FROM " . self::tableName() . " p 
                LEFT JOIN users u ON p.author_id = u.id 
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $statement = self::prepare($sql);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Count the total number of posts
     * 
     * @return int
     */
    public static function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::tableName();
        $statement = self::prepare($sql);
        $statement->execute();
        return (int)$statement->fetch()['count'];
    }

    /**
     * Generate a unique slug from the title
     * 
     * @param string $title The title to generate a slug from
     * @return string
     */
    private function generateSlug(string $title): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $originalSlug = $slug;
        $count = 1;

        while (self::findOne(['slug' => $slug])) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Prepare a SQL statement
     * 
     * @param string $sql The SQL statement to prepare
     * @return \PDOStatement
     */
    public static function prepare(string $sql): \PDOStatement
    {
        return Application::$app->db->prepare($sql);
    }
} 