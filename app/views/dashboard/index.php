<section class="hero">
<div>
<span class="eyebrow">Radiomed Clinical Risk Screening MVC</span>
<h1>Control seguro para exposición radiológica y quí­mica</h1>
</div>
<?php if(has_role(['ADMINISTRADOR','EVALUADOR'])): ?>
<a class="btn btn-primary" href="<?= e(url('caso','create')) ?>">Registrar nuevo tamizaje</a>
<?php endif; ?>
</section>
<div class="cards">
<div class="card stat">
<span>Casos</span>
<strong>
<?= (int)$cards['casos'] ?>
</strong>
</div>
<div class="card stat">
<span>Alertas pendientes</span>
<strong>
<?= (int)$cards['alertas'] ?>
</strong>
</div>
<div class="card stat">
<span>Alto / crí­tico</span>
<strong>
<?= (int)$cards['alto'] ?>
</strong>
</div>
<div class="card stat">
<span>Sustancias activas</span>
<strong>
<?= (int)$cards['sustancias'] ?>
</strong>
</div>
</div>
<div class="grid-2">
<div class="card">
<h3>Distribución por riesgo</h3>
<?php foreach($riesgos as $r): ?>
<div class="risk-row">
<span class="pill <?= level_class($r['nivel_riesgo']) ?>">
<?= e($r['nivel_riesgo']) ?>
</span>
<strong>
<?= (int)$r['total'] ?>
</strong>
</div>
<?php endforeach; if(!$riesgos): ?>
<p class="muted">Aún no hay casos registrados.</p>
<?php endif; ?>
</div>
<div class="card">
<h3>Últimos casos</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Código</th>
<th>Sustancia</th>
<th>Riesgo</th>
<th>Estado</th>
</tr>
</thead>
<tbody>
<?php foreach($ultimos as $c): ?>
<tr>
<td>
<a href="<?= e(url('caso','show',['id'=>(int)$c['id']])) ?>">
<?= e($c['codigo']) ?>
</a>
</td>
<td>
<?= e($c['sustancia']) ?>
</td>
<td>
<span class="pill <?= level_class($c['nivel_riesgo']) ?>">
<?= e($c['nivel_riesgo']) ?>
</span>
</td>
<td>
<?= e($c['estado']) ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>
