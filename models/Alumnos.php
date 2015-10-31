<?php  

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Alumnos extends ActiveRecord
{
	/**
	 * Obtener la conexion a la Bas de Datos
	 * @return [conexion] [objeto de conexion a la Base de Datos]
	 */
	public static function getDb()
	{
		return Yii::$app->db;
	}	
	public static function tableName()
	{
		return "alumnos";
	}
	

}
