<div class="card">
<h3>Casos registrados</h3>
<?php if(has_role(['ADMINISTRADOR','EVALUADOR'])): ?>
<a class="btn btn-primary" href="<?= e(url('caso','create')) ?>">Nuevo tamizaje</a>
<?php endif; ?>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Código</th>
<th>Paciente</th>
<th>Sustancia</th>
<th>Valor</th>
<th>Riesgo</th>
<th>Estado</th>
<th>Fecha</th>
</tr>
</thead>
<tbody>
<?php foreach($casos as $c): ?>
<tr>
<td>
<a href="<?= e(url('caso','show',['id'=>(int)$c['id']])) ?>">
<?= e($c['codigo']) ?>
</a>
</td>
<td>
<?= e($c['nombres']) ?>
</td>
<td>
<?= e($c['sustancia']) ?>
</td>
<td>
<?= e($c['valor_medido']) ?>
<?= e($c['simbolo']) ?>
</td>
<td>
<span class="pill <?= level_class($c['nivel_riesgo']) ?>">
<?= e($c['nivel_riesgo']) ?>
</span>
</td>
<td>
<?= e($c['estado']) ?>
</td>
<td>
<?= e($c['created_at']) ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

