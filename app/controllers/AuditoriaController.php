<?php
declare(strict_types=1);
class AuditoriaController extends Controller { public function index(): void { require_role('ADMINISTRADOR'); $this->view('auditoria/index',['logs'=>(new Auditoria())->list()]); } }
