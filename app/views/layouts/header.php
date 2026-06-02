<?php $user=current_user(); $current=$_GET['controller']??'dashboard'; ?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?= e(APP_NAME) ?> | MVC</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="layout">
<aside class="sidebar">
<div class="brand">
<div class="logo-mark">
<svg viewBox="0 0 64 64" role="img" aria-label="Logo Radiomed">
<circle cx="32" cy="32" r="29" fill="none" stroke="currentColor" stroke-width="4"/>
<path d="M32 14v36M14 32h36" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
<path d="M43 21c6 6 6 16 0 22M21 21c-6 6-6 16 0 22" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
<circle cx="32" cy="32" r="6" fill="currentColor"/>
</svg>
</div>
<div>
<h1>Radiomed</h1>
<p>Clí­nica especializada</p>
</div>
</div>
<nav class="nav">
<?php $items=['dashboard'=>'Panel de control','caso'=>'Casos y tamizaje','alerta'=>'Alertas','info'=>'Ambiente informativo','sustancia'=>'Sustancias','normativa'=>'Normativas y escalas','usuario'=>'Usuarios y perfiles','auditoria'=>'Auditoría']; foreach($items as $key=>$label){ if(menu_allowed($key)){ $href=$key==='caso'?url('caso','index'):url($key); $active=$current===$key?'active':''; echo '<a class="'.$active.'" href="'.e($href).'">'.e($label).'</a>'; } } ?>
</nav>
<div class="sidebar-footer">
<strong>
<?= e($user['nombre']??'') ?>
</strong>
<span>
<?= e($user['rol']??'') ?>
</span>
<a class="logout" href="<?= e(url('auth','logout')) ?>">Cerrar sesión</a>
</div>
</aside>
<main class="main">
<header class="topbar">
<div>
<span class="eyebrow">Plataforma segura de tamizaje preliminar</span>
<h2>
<?= e(APP_SUBTITLE) ?>
</h2>
</div>
<div class="badge">Modo <?= e(APP_ENV) ?>
</div>
</header>
<section class="notice">Resultado orientativo. No reemplaza diagnóstico médico, toxicológico ni radiológico especializado. Los valores y escalas deben ser validados antes de uso real.</section>
<?php render_flash(); ?>

