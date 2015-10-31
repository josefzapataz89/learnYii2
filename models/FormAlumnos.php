<?php  

namespace app\models;

use Yii;
use yii\base\model;

class FormAlumnos extends model
{
	public $id_alumno;
	public $nombre;
	public $apellidos;
	public $clase;
	public $note_final;

	public function rules()
	{
		return [
			["id_alumno", "integer", "message"=>"Id incorrecto."],
			["nombre", "required", "message"=>"Campo requerido."],
			["nombre", "match", "pattern"=>"/^[a-záéíóúñ\s]+$/i", "message"=>"Formato incorrecto. solo letras."],
			["nombre", "match", "pattern"=>"/^.{3,50}$/", "message"=>"Longitud entre 3 y 50 caracteres."],
			
			["apellidos", "required", "message"=>"Campo requerido."],
			["apellidos", "match", "pattern"=>"/^[a-záéíóúñ\s]+$/i", "message"=>"Formato incorrecto. solo letras."],
			["apellidos", "match", "pattern"=>"/^.{3,80}$/", "message"=>"Longitud entre 3 y 80 caracteres."],
			
			["clase", "required", "message"=>"Campo requerido."],
			["clase", "integer", "message"=>"Solo numeros."],

			["note_final", "required", "message"=>"Campo requerido."],
			["note_final", "number", "message"=>"Solo numeros."],

		];
	}
}

?>