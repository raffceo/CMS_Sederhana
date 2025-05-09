<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Application;

/**
 * User model for handling user data and authentication
 */
class User extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';
    public int $status = self::STATUS_INACTIVE;
    public ?string $current_password = null;
    public ?string $new_password = null;

    /**
     * Get the table name for the model
     * 
     * @return string
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * Save the user to the database
     * 
     * @return bool
     */
    public function save(): bool
    {
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    /**
     * Get the validation rules for the model
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class, 'attribute' => 'email'
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    /**
     * Find a user by conditions
     * 
     * @param array $where The conditions to search for
     * @return User|null
     */
    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    /**
     * Prepare a SQL statement
     * 
     * @param string $sql The SQL statement to prepare
     * @return \PDOStatement
     */
    public static function prepare($sql): \PDOStatement
    {
        return Application::$app->db->prepare($sql);
    }

    public function updateProfile(): bool
    {
        $statement = self::prepare("UPDATE " . self::tableName() . " SET 
            firstname = :firstname,
            lastname = :lastname,
            email = :email
            WHERE id = :id");

        $statement->bindValue(':firstname', $this->firstname);
        $statement->bindValue(':lastname', $this->lastname);
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':id', Application::$app->session->get('user'));

        return $statement->execute();
    }

    public function updatePassword(): bool
    {
        $statement = self::prepare("UPDATE " . self::tableName() . " SET 
            password = :password
            WHERE id = :id");

        $statement->bindValue(':password', password_hash($this->new_password, PASSWORD_DEFAULT));
        $statement->bindValue(':id', Application::$app->session->get('user'));

        return $statement->execute();
    }

    public function validatePassword(): bool
    {
        $this->errors = [];
        
        if (empty($this->current_password)) {
            $this->addError('current_password', 'Current password is required');
        } else {
            $user = self::findOne(['id' => Application::$app->session->get('user')]);
            if (!password_verify($this->current_password, $user->password)) {
                $this->addError('current_password', 'Current password is incorrect');
            }
        }

        if (empty($this->new_password)) {
            $this->addError('new_password', 'New password is required');
        } elseif (strlen($this->new_password) < 8) {
            $this->addError('new_password', 'Password must be at least 8 characters');
        }

        if (empty($this->confirmPassword)) {
            $this->addError('confirmPassword', 'Please confirm your password');
        } elseif ($this->new_password !== $this->confirmPassword) {
            $this->addError('confirmPassword', 'Passwords do not match');
        }

        return empty($this->errors);
    }

    public function getDisplayName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
} 