<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

/**
 * Category model for handling blog categories
 */
class Category extends Model
{
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public ?int $parent_id = null;

    /**
     * Get the table name for the model
     * 
     * @return string
     */
    public static function tableName(): string
    {
        return 'categories';
    }

    /**
     * Save the category to the database
     * 
     * @return bool
     */
    public function save(): bool
    {
        $this->slug = $this->generateSlug($this->name);
        
        $sql = "INSERT INTO " . self::tableName() . " 
                (name, slug, description, parent_id) 
                VALUES 
                (:name, :slug, :description, :parent_id)";
        
        $statement = self::prepare($sql);
        $statement->bindValue(':name', $this->name);
        $statement->bindValue(':slug', $this->slug);
        $statement->bindValue(':description', $this->description);
        $statement->bindValue(':parent_id', $this->parent_id);

        return $statement->execute();
    }

    /**
     * Update the category in the database
     * 
     * @return bool
     */
    public function update(): bool
    {
        $this->slug = $this->generateSlug($this->name);
        
        $sql = "UPDATE " . self::tableName() . " 
                SET name = :name, 
                    slug = :slug, 
                    description = :description, 
                    parent_id = :parent_id 
                WHERE id = :id";
        
        $statement = self::prepare($sql);
        $statement->bindValue(':name', $this->name);
        $statement->bindValue(':slug', $this->slug);
        $statement->bindValue(':description', $this->description);
        $statement->bindValue(':parent_id', $this->parent_id);
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
            'name' => [self::RULE_REQUIRED],
        ];
    }

    /**
     * Find all categories
     * 
     * @return array
     */
    public static function findAll(): array
    {
        $sql = "SELECT * FROM " . self::tableName() . " ORDER BY name ASC";
        $statement = self::prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * Find a category by conditions
     * 
     * @param array $where The conditions to search for
     * @return Category|null
     */
    public static function findOne(array $where): ?Category
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
     * Count the total number of categories
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
     * Generate a unique slug from the name
     * 
     * @param string $name The name to generate a slug from
     * @return string
     */
    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
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

    /**
     * Get child categories
     * 
     * @return array
     */
    public function getChildren(): array
    {
        $sql = "SELECT * FROM " . self::tableName() . " WHERE parent_id = :id ORDER BY name ASC";
        $statement = self::prepare($sql);
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    /**
     * Get parent category
     * 
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        if (!$this->parent_id) {
            return null;
        }
        return self::findOne(['id' => $this->parent_id]);
    }

    /**
     * Get the number of posts in this category
     * 
     * @return int
     */
    public function getPostCount(): int
    {
        $sql = "SELECT COUNT(*) as count FROM posts WHERE category_id = :id";
        $statement = self::prepare($sql);
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        return (int)$statement->fetch()['count'];
    }
} 