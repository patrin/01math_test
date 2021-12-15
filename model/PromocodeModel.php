<?php

/**
 *  This class describes promocodes.
 *
 *  There are several types of promocode:
 *      - Promocode which adds free days to user's paid period
 *      - Promocode which extends user's paid period until fixed date
 *
 *  Moreover promocodes optionally have period of activity and may be available only for new users.
 *
 *
 *  To implement the functionality above this structure of 'promocodes' db table was created:
 *      id - required - numeric auto-increment primary key
 *      promocode - required - unique key: string of text - promocode itself
 *      plus_days - optional - default null: number of days to be added to user's paid_to period (not appliable if null)
 *      available_from - optional - default null; promocode activity period start (if null - active anytime till available_to)
 *      available_to - optional - default null; promocode activity period end (if null - active anytime after available_from)
 *      fixed_date - optional - default null: fixed date for user's paid period extension  (not appliable if null)
 *      registration_only - required - default 0 - integer: 1 if promocode available only for new users, otherwise 0
 *
 */
class PromocodeModel extends Model
{
    const ID = 'id';
    const TABLE = 'promocode';

    static function getIdField(): string
    {
        return self::ID;
    }

    static function getTable(): string
    {
        return self::TABLE;
    }

    public function __construct(?int $id = null)
    {
        return parent::__construct($id);
    }

    public function __get($key)
    {
        if ($key == 'registration_only') { // cast registration_only to bool
            $value = parent::__get($key);
            return is_numeric($value) ? boolval($value) : $value;
        } elseif ($key == 'plus_days') { // cast plus_days to int
            $value = parent::__get($key);
            return is_numeric($value) ? intval($value) : $value;
        } else {
            return parent::__get($key);
        }
    }

    /**
     * This method finds and returns promocode entity by promocode text string
     *
     * @param string $promocode
     * @return PromocodeModel|null
     */
    public function getPromocodeByPromocode(string $promocode): ?PromocodeModel
    {
        $id = $this->db()->selectCell('SELECT ' . self::getIdField() . ' FROM ' . self::getTable() . " WHERE promocode = '{$promocode}'");

        return $id ? new self($id) : null;
    }

    /**
     * This method returns true if promocode is available (active) at the moment
     * otherwise returns false
     *
     * @return bool
     */
    public function isAvailable()
    {
        $date_time = date('Y-m-d H:m:s', time());

        $cond1 = $this->available_from < $date_time;
        $cond2 = $date_time < $this->available_to;

        return $cond1 === true ? ($cond2 === true ? true : false) : false;
    }

    /**
     * This method checks if promocode can be applied to registered user
     *
     * @param UserModel $user
     * @return bool
     */
    public function isAppliableForUser(UserModel $user): bool
    {
        if ($this->registration_only && !$user->isExists()) {
            return true;
        } elseif (!$this->registration_only) {
            return true;
        }

        return true;
    }

    /**
     * This method checks if promocode fixed_date is more than user's paid_to period
     *
     *  NOTE: user's paid period may reduce if apply promocode without this check
     *
     * @param UserModel $user
     * @return bool
     */
    public function paidPeriodCheck(UserModel $user): bool
    {
        if (!is_null($this->fixed_date)) {
            return !($user->paid_to > $this->fixed_date);
        } else {
            return true;
        }
    }


    public function applyPromocode(UserModel $user): bool
    {
        $result = false;

        if ($this->isAvailable() && $this->isAppliableForUser($user) && $this->paidPeriodCheck($user)) {
            $result = $this->db()->query('INSERT INTO user_promocode(user_id, promocode_id) VALUES (?d, ?d)',
                $user->id, $this->id);
        }

        return $result;
    }
}