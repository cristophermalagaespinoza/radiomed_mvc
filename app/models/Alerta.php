<?php
declare(strict_types=1);
class Alerta extends Model {
    public function create(int $casoId,string $nivel,string $mensaje): void { if(!in_array($nivel,['MODERADO','ALTO','CRITICO'],true)) return; $stmt=$this->db->prepare('INSERT INTO alertas(caso_id,nivel,mensaje) VALUES(?,?,?)'); $stmt->bind_param('iss',$casoId,$nivel,$mensaje); $stmt->execute(); }
    public function list(): array { return $this->all('SELECT a.*,p.codigo,p.nombres,s.nombre sustancia,c.nivel_riesgo FROM alertas a JOIN casos c ON c.id=a.caso_id JOIN personas p ON p.id=c.persona_id JOIN sustancias s ON s.id=c.sustancia_id ORDER BY a.id DESC LIMIT 100'); }
    public function updateEstado(int $id,string $estado): void { $stmt=$this->db->prepare('UPDATE alertas SET estado=? WHERE id=?'); $stmt->bind_param('si',$estado,$id); $stmt->execute(); }
}
