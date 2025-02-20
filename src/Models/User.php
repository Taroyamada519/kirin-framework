<?php
namespace App\Models;

class User extends BaseModel {
    protected $table = 'users';
    protected $fillable = ['name', 'email'];

    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }

    public function validateUnique($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = $this->db->fetch($sql, $params);
        return $result['count'] === 0;
    }
}
