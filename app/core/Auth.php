<?php
declare(strict_types=1);

function current_user(): ?array { return $_SESSION['user'] ?? null; }
function is_logged(): bool { return !empty($_SESSION['user']); }
function require_login(): void { if(!is_logged()) redirect(url('auth','login')); }
function has_role(array|string $roles): bool { $roles=is_array($roles)?$roles:[$roles]; return is_logged() && in_array($_SESSION['user']['rol'],$roles,true); }
function require_role(array|string $roles): void { if(!has_role($roles)){ http_response_code(403); exit('Acceso denegado. Tu perfil no tiene permisos para esta acción.'); } }
function menu_allowed(string $controller): bool { $rol=$_SESSION['user']['rol']??''; $matrix=[ 'ADMINISTRADOR'=>['dashboard','caso','alerta','info','sustancia','normativa','usuario','auditoria'], 'EVALUADOR'=>['dashboard','caso','alerta','info'], 'ESPECIALISTA'=>['dashboard','caso','alerta','info'], 'AUTORIDAD'=>['dashboard','caso','alerta','info'], 'CONSULTOR'=>['dashboard','info'] ]; return in_array($controller,$matrix[$rol]??[],true); }
