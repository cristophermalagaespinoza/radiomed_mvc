<?php
declare(strict_types=1);
class Usuario extends Model {
    public function findByEmail(string $correo): ?array { return $this->one('SELECT u.*, r.nombre rol FROM usuarios u JOIN roles r ON r.id=u.rol_id WHERE u.correo=? AND u.estado="ACTIVO" LIMIT 1','s',[$correo]); }
    public function roles(): array { return $this->all('SELECT * FROM roles ORDER BY id'); }
    public function list(): array { return $this->all('SELECT u.*,r.nombre rol FROM usuarios u JOIN roles r ON r.id=u.rol_id ORDER BY u.id DESC'); }
    public function create(string $nombre,string $correo,string $hash,int $rol_id,string $estado): int { if(!$this->exists('roles',$rol_id)) throw new RuntimeException('Rol no válido.'); $stmt=$this->db->prepare('INSERT INTO usuarios(nombre,correo,password_hash,rol_id,estado) VALUES(?,?,?,?,?)'); $stmt->bind_param('sssis',$nombre,$correo,$hash,$rol_id,$estado); $stmt->execute(); return (int)$this->db->insert_id; }
}
