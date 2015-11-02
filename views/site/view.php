<?php 
	use yii\helpers\Url;
	//para editar
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
?>

<a href="<?= Url::toRoute('site/create') ?>">Crear un nuevo Alumno</a>

<?php 
	$f = ActiveForm::begin([
		"method" => "get",
		"action" => Url::toRoute("site/view"),
		"enableClientValidation" => true
		]);
?>
	<div class="form-group">
		<?= $f->field($form, "q")->input("search") ?>
	</div>
	<?= Html::submitButton("Buscar", ["class"=>"btn btn-primary"]) ?>
<?php $f->end(); ?>
<h3><?= $search ?></h3>

<h3>Lista de Alumnos</h3>
<table class="table table-bordered">
	<thead>
		<th>Id Alumno</th>
		<th>Nombre</th>
		<th>Apellidos</th>
		<th>Clase</th>
		<th>Nota Final</th>
		<th></th>
		<th></th>
	</thead>
	<tbody>
		<?php foreach ($model as $alumno): ?>
			<tr>
				<td><?= $alumno->id_alumno ?></td>
				<td><?= $alumno->nombre ?></td>
				<td><?= $alumno->apellidos ?></td>
				<td><?= $alumno->clase ?></td>
				<td><?= $alumno->note_final ?></td>
				<td><a href="#">Editar</a></td>
				<td><a href="#">Eliminar</a></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>