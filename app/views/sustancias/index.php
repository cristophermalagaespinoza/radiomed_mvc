<div class="grid-2">
<div class="card">
<h3>Registrar sustancia</h3>
<form method="post" action="<?= e(url('sustancia','store')) ?>" class="form-grid one">
<?= csrf_field() ?>
<label>Nombre<input name="nombre" required maxlength="120">
</label>
<label>Tipo<select name="tipo">
<option>RADIACTIVA</option>
<option>QUIMICA</option>
</select>
</label>
<label>Descripción<textarea name="descripcion" required maxlength="2000">
</textarea>
</label>
<label>Caso mundial<input name="caso_mundial" maxlength="180">
</label>
<label>Antecedente Perú<input name="antecedente_peru" maxlength="180">
</label>
<button class="btn btn-primary">Guardar</button>
</form>
</div>
<div class="card">
<h3>Catálogo</h3>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Nombre</th>
<th>Tipo</th>
<th>Caso</th>
<th>Perú</th>
</tr>
</thead>
<tbody>
<?php foreach($sustancias as $s): ?>
<tr>
<td>
<?= e($s['nombre']) ?>
</td>
<td>
<?= e($s['tipo']) ?>
</td>
<td>
<?= e($s['caso_mundial']) ?>
</td>
<td>
<?= e($s['antecedente_peru']) ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>

