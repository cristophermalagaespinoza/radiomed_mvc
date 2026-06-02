<?php
declare(strict_types=1);

function e(?string $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): never { header('Location: ' . $url); exit; }
function base_url(string $path = ''): string { return rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/' . ltrim($path, '/'); }
function url(string $controller, string $action = 'index', array $params = []): string {
    $query = array_merge(['controller'=>$controller, 'action'=>$action], $params);
    return 'index.php?' . http_build_query($query);
}
function csrf_token(): string { if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); return $_SESSION['csrf_token']; }
function csrf_field(): string { return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void { if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) { http_response_code(403); exit('Solicitud rechazada por seguridad CSRF.'); } }
function flash(string $type, string $message): void { $_SESSION['flash'][] = ['type'=>$type, 'message'=>$message]; }
function render_flash(): void { $items=$_SESSION['flash']??[]; unset($_SESSION['flash']); foreach($items as $item){ echo '<div class="alert alert-'.e($item['type']).'">'.e($item['message']).'</div>'; } }
function post_text(string $key, int $max = 255, bool $required = true): string { $value=trim((string)($_POST[$key]??'')); $value=preg_replace('/\s+/', ' ', $value); if($required && $value==='') throw new RuntimeException("El campo {$key} es obligatorio."); if(mb_strlen($value)>$max) throw new RuntimeException("El campo {$key} supera el máximo permitido."); if($value !== strip_tags($value)) throw new RuntimeException("El campo {$key} contiene caracteres no permitidos."); return $value; }
function post_int(string $key, int $min, int $max): int { $raw=$_POST[$key]??null; if(!filter_var($raw,FILTER_VALIDATE_INT)) throw new RuntimeException("El campo {$key} debe ser entero válido."); $v=(int)$raw; if($v<$min||$v>$max) throw new RuntimeException("El campo {$key} está fuera del rango permitido."); return $v; }
function post_float(string $key, float $min, float $max): float { $raw=str_replace(',', '.', (string)($_POST[$key]??'')); if(!is_numeric($raw)) throw new RuntimeException("El campo {$key} debe ser numérico."); $v=(float)$raw; if($v<$min||$v>$max) throw new RuntimeException("El campo {$key} está fuera del rango permitido."); return $v; }
function post_enum(string $key, array $allowed): string { $value=(string)($_POST[$key]??''); if(!in_array($value,$allowed,true)) throw new RuntimeException("El campo {$key} tiene un valor no permitido."); return $value; }
function level_class(string $nivel): string { return match($nivel){ 'BAJO'=>'risk-low','MODERADO'=>'risk-medium','ALTO'=>'risk-high','CRITICO'=>'risk-critical', default=>'risk-none' }; }
function audit_log(string $accion, string $tabla, ?int $registroId=null, string $detalle=''): void { if(empty($_SESSION['user']['id'])) return; $ip=$_SERVER['REMOTE_ADDR']??'N/D'; $uid=(int)$_SESSION['user']['id']; $stmt=db()->prepare('INSERT INTO auditoria(usuario_id,accion,tabla_afectada,registro_id,detalle,ip) VALUES(?,?,?,?,?,?)'); $stmt->bind_param('ississ',$uid,$accion,$tabla,$registroId,$detalle,$ip); $stmt->execute(); }
