<?php
declare(strict_types=1);

abstract class Model {
    protected mysqli $db;
    public function __construct(){ $this->db = db(); }
    protected function all(string $sql, string $types='', array $params=[]): array { $stmt=$this->db->prepare($sql); if($types) $stmt->bind_param($types,...$params); $stmt->execute(); return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); }
    protected function one(string $sql, string $types='', array $params=[]): ?array { $stmt=$this->db->prepare($sql); if($types) $stmt->bind_param($types,...$params); $stmt->execute(); $row=$stmt->get_result()->fetch_assoc(); return $row ?: null; }
    protected function exists(string $table, int $id, string $extraWhere=''): bool { $stmt=$this->db->prepare("SELECT id FROM {$table} WHERE id=? {$extraWhere} LIMIT 1"); $stmt->bind_param('i',$id); $stmt->execute(); return (bool)$stmt->get_result()->fetch_assoc(); }
}
