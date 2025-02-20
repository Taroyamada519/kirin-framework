<?php
namespace App\Models;

use App\Core\Database;

abstract class BaseModel {
    protected $db;
    protected $table;
    protected $fillable = [];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function all() {
        return $this->db->fetchAll("SELECT * FROM {$this->table}");
    }

    public function find($id) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE id = ?",
            [$id]
        );
    }

    public function create($data) {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));
        return $this->db->insert($this->table, $filteredData);
    }

    public function update($id, $data) {
        $filteredData = array_intersect_key($data, array_flip($this->fillable));
        $filteredData['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->update(
            $this->table,
            $filteredData,
            'id = ?',
            [$id]
        );
    }

    public function delete($id) {
        $this->db->delete($this->table, 'id = ?', [$id]);
    }

    public function where($field, $value) {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$field} = ?",
            [$value]
        );
    }

    public function findBy($field, $value) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$field} = ?",
            [$value]
        );
    }
}
