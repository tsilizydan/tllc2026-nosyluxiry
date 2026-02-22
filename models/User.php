<?php
class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?object
    {
        return $this->first("email = ?", [$email]);
    }

    public function customers(): array
    {
        return $this->where("role = 'customer'", [], 'created_at DESC');
    }

    public function admins(): array
    {
        return $this->where("role IN ('admin','super_admin')", [], 'created_at DESC');
    }
}
