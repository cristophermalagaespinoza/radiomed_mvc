<?php
declare(strict_types=1);
class AuthController extends Controller {
    public function login(): void { if(is_logged()) redirect(url('dashboard')); $this->authView('auth/login'); }
    public function authenticate(): void { verify_csrf(); $correo=strtolower(post_text('correo',150)); $clave=(string)($_POST['clave']??''); $u=(new Usuario())->findByEmail($correo); if(!$u || !password_verify($clave,$u['password_hash'])){ flash('danger','Credenciales inválidas o usuario inactivo.'); redirect(url('auth','login')); } session_regenerate_id(true); $_SESSION['user']=['id'=>(int)$u['id'],'nombre'=>$u['nombre'],'correo'=>$u['correo'],'rol'=>$u['rol']]; audit_log('LOGIN','usuarios',(int)$u['id'],$correo); redirect(url('dashboard')); }
    public function logout(): void { audit_log('LOGOUT','usuarios',(int)($_SESSION['user']['id']??0),'Cierre de sesión'); session_destroy(); redirect(url('auth','login')); }
}
