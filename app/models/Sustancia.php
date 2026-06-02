<?php
declare(strict_types=1);
class Sustancia extends Model {
    public function active(): array { return $this->all('SELECT * FROM sustancias WHERE estado="ACTIVO" ORDER BY tipo,nombre'); }
    public function list(): array { return $this->all('SELECT * FROM sustancias ORDER BY tipo,nombre'); }
    public function create(string $nombre,string $tipo,string $descripcion,string $caso,string $peru): int { $stmt=$this->db->prepare('INSERT INTO sustancias(nombre,tipo,descripcion,caso_mundial,antecedente_peru) VALUES(?,?,?,?,?)'); $stmt->bind_param('sssss',$nombre,$tipo,$descripcion,$caso,$peru); $stmt->execute(); return (int)$this->db->insert_id; }
    public function find(int $id): ?array { return $this->one('SELECT * FROM sustancias WHERE id=? AND estado="ACTIVO"','i',[$id]); }
    public function unidades(): array { return $this->all('SELECT su.sustancia_id,u.id,u.simbolo,u.nombre FROM sustancia_unidades su JOIN unidades u ON u.id=su.unidad_id ORDER BY u.simbolo'); }
    public function vias(): array { return $this->all('SELECT sv.sustancia_id,v.id,v.nombre FROM sustancia_vias sv JOIN vias_exposicion v ON v.id=sv.via_id ORDER BY v.nombre'); }
    public function unidadOk(int $sid,int $uid): bool { return (bool)$this->one('SELECT 1 FROM sustancia_unidades WHERE sustancia_id=? AND unidad_id=?','ii',[$sid,$uid]); }
    public function viaOk(int $sid,int $vid): bool { return (bool)$this->one('SELECT 1 FROM sustancia_vias WHERE sustancia_id=? AND via_id=?','ii',[$sid,$vid]); }
}
