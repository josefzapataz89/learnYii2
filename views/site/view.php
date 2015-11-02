<?php 
	use yii\helpers\Url;
	//para editar
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\data\Pagination;
	use yii\widgets\LinkPager;
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
				<td><a href="<?= Url::toRoute(['site/update', 'id_alumno'=>$alumno->id_alumno]) ?>">Editar</a></td>
				<td>
					<!-- INICIO MODAL -->
					<a href="#" data-toggle="modal" data-target="#id_alumno_<?= $alumno->id_alumno ?>">Eliminar</a>
					<div class="modal fade" role="dialog" aria-hidden="true" id="id_alumno_<?= $alumno->id_alumno ?>">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title">Eliminar Alumno</h4>
					      </div>
					      <div class="modal-body">
					        <p>¿Realmente desea eliminar al alumno con id <?= $alumno->id_alumno ?>?</p>
					      </div>
					      <div class="modal-footer">
					      	<?= Html::beginForm(Url::toRoute("site/delete"), "POST") ?>
					      		<input type="hidden" name="id_alumno" value="<?= $alumno->id_alumno ?>">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
						        <button type="submit" class="btn btn-primary">Eliminar</button>
						    <?= Html::endForm() ?>
					      </div>
					    </div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					<!-- FIN MODAL -->
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?= LinkPager::widget([
	"pagination" => $pages,
	]) ?>