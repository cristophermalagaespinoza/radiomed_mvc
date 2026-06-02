<?php
declare(strict_types=1);

final class Router {
    private array $map = [
        'auth' => AuthController::class,
        'dashboard' => DashboardController::class,
        'caso' => CasoController::class,
        'alerta' => AlertaController::class,
        'info' => InfoController::class,
        'sustancia' => SustanciaController::class,
        'normativa' => NormativaController::class,
        'usuario' => UsuarioController::class,
        'auditoria' => AuditoriaController::class,
    ];
    public function dispatch(): void {
        $controller = preg_replace('/[^a-z_]/','', strtolower($_GET['controller'] ?? (is_logged() ? 'dashboard' : 'auth')));
        $action = preg_replace('/[^a-zA-Z0-9_]/','', $_GET['action'] ?? (is_logged() ? 'index' : 'login'));
        if ($controller !== 'auth') require_login();
        if ($controller !== 'auth' && !menu_allowed($controller)) { flash('danger','No tienes permisos para acceder a ese módulo.'); redirect(url('dashboard')); }
        $class = $this->map[$controller] ?? DashboardController::class;
        $obj = new $class();
        if (!method_exists($obj,$action)) { http_response_code(404); exit('Acción no encontrada.'); }
        $obj->$action();
    }
}
