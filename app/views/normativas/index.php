<div class="grid-2">
<div class="card">
<h3>Registrar normativa</h3>
<form method="post" action="<?= e(url('normativa','store')) ?>" class="form-grid one">
<?= csrf_field() ?>
<label>Nombre<input name="nombre" required maxlength="180">
</label>
<label>Paí­s<input name="pais" required maxlength="80">
</label>
<label>Institución<input name="institucion" required maxlength="120">
</label>
<label>Descripción<textarea name="descripcion" maxlength="2000">
</textarea>
</label>
<button class="btn btn-primary">Guardar normativa</button>
</form>
</div>
<div class="card">
<h3>Registrar escala de riesgo</h3>
<form method="post" action="<?= e(url('normativa','storeEscala')) ?>" class="form-grid one">
<?= csrf_field() ?>
<label>Sustancia<select id="sustancia_id" name="sustancia_id" onchange="filterBySustancia()" required>
<?php foreach($sustancias as $s): ?>
<option value="<?= (int)$s['id'] ?>" data-tipo="<?= e($s['tipo']) ?>">
<?= e($s['nombre']) ?>
</option>
<?php endforeach; ?>
</select>
</label>
<label>Normativa<select name="normativa_id" required>
<?php foreach($normas as $n): ?>
<option value="<?= (int)$n['id'] ?>">
<?= e($n['nombre']) ?>
</option>
<?php endforeach; ?>
</select>
</label>
<label>Unidad<select name="unidad_id" required>
<?php foreach($unidades as $u): ?>
<option data-sustancia="<?= (int)$u['sustancia_id'] ?>" value="<?= (int)$u['id'] ?>">
<?= e($u['simbolo']) ?> - <?= e($u['nombre']) ?>
</option>
<?php endforeach; ?>
</select>
</label>
<label>Nivel<select name="nivel">
<option>BAJO</option>
<option>MODERADO</option>
<option>ALTO</option>
<option>CRITICO</option>
</select>
</label>
<label>Desde<input type="number" step="0.0001" min="0" name="desde" required>
</label>
<label>Hasta<input type="number" step="0.0001" min="0" name="hasta" placeholder="Vací­o = sin lí­mite">
</label>
<label>Acción<textarea name="accion" required maxlength="1000">
</textarea>
</label>
<button class="btn btn-primary">Guardar escala</button>
</form>
</div>
</div>
<div class="card">
<h3>Escalas registradas</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Sustancia</th>
<th>Normativa</th>
<th>Unidad</th>
<th>Nivel</th>
<th>Rango</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<?php foreach($escalas as $e2): ?>
<tr>
<td>
<?= e($e2['sustancia']) ?>
</td>
<td>
<?= e($e2['normativa']) ?>
</td>
<td>
<?= e($e2['simbolo']) ?>
</td>
<td>
<span class="pill <?= level_class($e2['nivel']) ?>">
<?= e($e2['nivel']) ?>
</span>
</td>
<td>
<?= e($e2['desde']) ?> - <?= e($e2['hasta'] ?? 'âˆž') ?>
</td>
<td>
<?= e($e2['accion']) ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>

