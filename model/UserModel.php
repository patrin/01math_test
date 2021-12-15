<?php

require_once 'Model.php';

class UserModel extends Model
{
    const ID = 'id';
    const TABLE = 'user';

    public function __construct(?int $id = null)
    {
        parent::__construct($id);
    }

    public static function getIdField(): string
    {
        return self::ID;
    }

    public static function getTable(): string
    {
        return self::TABLE;
    }

    public function __get($key)
    {
        if ($key === 'promocodes') {
            return $this->promocodes();
        }

        return parent::__get($key);
    }

    public static function passwordHash(string $plain_text): string
    {
        return password_hash($plain_text, PASSWORD_DEFAULT);
    }

    public function passwordVerify(string $plain_text): bool
    {
        return password_verify($plain_text, $this->password);
    }

    public function applyPromocode(PromocodeModel $promocode): bool
    {
        // implemented in PromocodeModel
    }

    public function promocodes(): array
    {
        // @todo implement
        return [];
    }
}