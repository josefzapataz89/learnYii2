<?php  

namespace app\models;
use Yii;
use yii\base\model;

class ValidarFormularioAjax extends model
{
	public $nombre;
	public $email;

	public function rules()
	{
		return [
			["nombre", "required", "message"=>"Campo Requerido"],
			["nombre", "match", "pattern"=>"/^.{3,50}$/", "message"=>"minimo 3, maximo 50 caracteres"],
			["nombre", "match", "pattern"=>"/^.[0-9a-zñ\s]+$/i", "message"=>"solo se aceptan letras y numeros"],

			["email", "required", "message"=>"Campo requerido"],
			["email", "match", "pattern"=>"/^.{5,80}$/", "message"=>"minimo 3, maximo 50 caracteres"],
			["email", "email", "message"=>"formato invalido. ejemplo: usuario@dominio.ext"],

			["email", "email_existe"],

		];
	}

	public function attributeLabels()
	{
		return [
			"nombre" => "Nombre: ",
			"email" => "Email: "
		];
	}

	public function email_existe($attribute, $params)
	{
		$email = ["manuel@mail.com", "antonio@mail.com"];
		foreach ($email as $value) 
		{
			if( $this->email == $value )	
			{
				$this->addError($attribute, "El email seleccionado ya existe");
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}

?>