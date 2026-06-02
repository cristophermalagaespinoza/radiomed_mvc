<?php
declare(strict_types=1);
class Auditoria extends Model { public function list(): array { return $this->all('SELECT a.*,u.nombre usuario FROM auditoria a JOIN usuarios u ON u.id=a.usuario_id ORDER BY a.id DESC LIMIT 200'); } }
