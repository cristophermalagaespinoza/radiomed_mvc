<?php
declare(strict_types=1);
class InfoController extends Controller { public function index(): void { $this->view('info/index',['sustancias'=>(new Sustancia())->active()]); } }
