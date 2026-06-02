<?php
declare(strict_types=1);
class Normativa extends Model {
    public function active(): array { return $this->all('SELECT * FROM normativas WHERE estado="ACTIVO" ORDER BY nombre'); }
    public function list(): array { return $this->all('SELECT * FROM normativas ORDER BY id DESC'); }
    public function create(string $nombre,string $pais,string $institucion,string $descripcion): int { $stmt=$this->db->prepare('INSERT INTO normativas(nombre,pais,institucion,descripcion) VALUES(?,?,?,?)'); $stmt->bind_param('ssss',$nombre,$pais,$institucion,$descripcion); $stmt->execute(); return (int)$this->db->insert_id; }
    public function existsActive(int $id): bool { return $this->exists('normativas',$id,'AND estado="ACTIVO"'); }
}
