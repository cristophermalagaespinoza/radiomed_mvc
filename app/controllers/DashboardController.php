<?php
declare(strict_types=1);
class DashboardController extends Controller { public function index(): void { $m=new Caso(); $this->view('dashboard/index',['cards'=>$m->stats(),'riesgos'=>$m->riesgos(),'ultimos'=>$m->ultimos()]); } }
