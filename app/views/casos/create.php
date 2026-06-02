<div class="card">
<h3>Nuevo tamizaje preliminar</h3>
<p class="muted">Los datos se limitan por catálogos. El backend valida sustancia, vía, unidad, rango y escala antes de guardar.</p>
<form method="post" action="<?= e(url('caso','store')) ?>" class="form-grid">
<?= csrf_field() ?>
<h4>Datos de la persona evaluada</h4>
<label>Nombres o identificación interna<input name="nombres" required maxlength="150">
</label>
<label>Edad<input type="number" name="edad" min="0" max="120" required>
</label>
<label>Sexo<select name="sexo" required>
<option>MASCULINO</option>
<option>FEMENINO</option>
<option>NO_ESPECIFICA</option>
</select>
</label>
<label>Ocupación<input name="ocupacion" maxlength="120">
</label>
<label>Condición vulnerable<select name="condicion_vulnerable" required>
<option>NINGUNA</option>
<option>NIÑO</option>
<option>GESTANTE</option>
<option>ADULTO_MAYOR</option>
<option>TRABAJADOR_EXPUSTO</option>
<option>OTRA</option>
</select>
</label>
<label class="wide">Antecedentes<textarea name="antecedentes" maxlength="2000">
</textarea>
</label>
<h4>Datos de exposición y medición</h4>
<label>Sustancia<select id="sustancia_id" name="sustancia_id" required onchange="filterBySustancia()">
<?php foreach($sustancias as $s): ?>
<option value="<?= (int)$s['id'] ?>" data-tipo="<?= e($s['tipo']) ?>">
<?= e($s['nombre']) ?> - <?= e($s['tipo']) ?>
</option>
<?php endforeach; ?>
</select>
</label>
<label>Normativa / escala<select name="normativa_id" required>
<?php foreach($normativas as $n): ?>
<option value="<?= (int)$n['id'] ?>">
<?= e($n['nombre']) ?> (<?= e($n['pais']) ?>)</option>
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
<label>Vía de exposición<select name="via_id" required>
<?php foreach($vias as $v): ?>
<option data-sustancia="<?= (int)$v['sustancia_id'] ?>" value="<?= (int)$v['id'] ?>">
<?= e($v['nombre']) ?>
</option>
<?php endforeach; ?>
</select>
</label>
<label>Tipo de evento<select name="tipo_evento" required>
<option>LABORAL</option>
<option>AMBIENTAL</option>
<option>INDUSTRIAL</option>
<option>MEDICO</option>
<option>DOMESTICO</option>
<option>DESCONOCIDO</option>
</select>
</label>
<label>Fecha y hora de exposición<input type="datetime-local" name="fecha_exposicion" required>
</label>
<label>Lugar del evento<input name="lugar_evento" required maxlength="180">
</label>
<label>Tiempo de exposición horas<input type="number" step="0.01" min="0.01" name="tiempo_exposicion_horas" required>
</label>
<label>Valor medido<input type="number" step="0.0001" min="0" name="valor_medido" required>
</label>
<label>Tipo de muestra<select name="tipo_muestra" required>
<option data-tipo-muestra="MIXTA">SANGRE</option>
<option data-tipo-muestra="MIXTA">ORINA</option>
<option data-tipo-muestra="MIXTA">CABELLO</option>
<option data-tipo-muestra="MIXTA">AGUA</option>
<option data-tipo-muestra="MIXTA">AIRE</option>
<option data-tipo-muestra="MIXTA">SUELO</option>
<option data-tipo-muestra="RADIACTIVA">MEDICION_RADIOLOGICA</option>
<option data-tipo-muestra="MIXTA">OTRA</option>
</select>
</label>
<label class="wide">Síntomas<textarea name="sintomas" required maxlength="2500">
</textarea>
</label>
<label>Zona corporal<input name="zona_corporal" maxlength="120">
</label>
<label>Equipo utilizado<input name="equipo_utilizado" maxlength="160">
</label>
<div class="wide form-actions">
<button class="btn btn-primary" type="submit">Calcular y registrar</button>
</div>
</form>
</div>

