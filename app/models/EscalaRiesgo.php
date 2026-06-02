<?php
declare(strict_types=1);
class EscalaRiesgo extends Model {
    public function list(): array { return $this->all('SELECT er.*,s.nombre sustancia,n.nombre normativa,u.simbolo FROM escalas_riesgo er JOIN sustancias s ON s.id=er.sustancia_id JOIN normativas n ON n.id=er.normativa_id JOIN unidades u ON u.id=er.unidad_id ORDER BY er.id DESC LIMIT 100'); }
    public function create(int $sustancia_id,int $normativa_id,int $unidad_id,string $nivel,float $desde,?float $hasta,string $accion): int { $stmt=$this->db->prepare('INSERT INTO escalas_riesgo(sustancia_id,normativa_id,unidad_id,nivel,desde,hasta,accion) VALUES(?,?,?,?,?,?,?)'); $stmt->bind_param('iiisdds',$sustancia_id,$normativa_id,$unidad_id,$nivel,$desde,$hasta,$accion); $stmt->execute(); return (int)$this->db->insert_id; }
    public function match(int $sid,int $nid,int $uid,float $valor): ?array { return $this->one('SELECT er.*,u.simbolo FROM escalas_riesgo er JOIN unidades u ON u.id=er.unidad_id WHERE er.sustancia_id=? AND er.normativa_id=? AND er.unidad_id=? AND er.estado="ACTIVO" AND ? >= er.desde AND (er.hasta IS NULL OR ? <= er.hasta) ORDER BY er.desde DESC LIMIT 1','iiidd',[$sid,$nid,$uid,$valor,$valor]); }
}
