<?php
/**
 * Created by PhpStorm.
 * User: Jose
 * Date: 29/10/2015
 * Time: 21:27
 */
?>
<h1><?= $saludo ?></h1>
<?php foreach ($numeros as $valor): ?>
	<p><strong><?= $valor; ?></strong></p>
<?php endforeach; ?>

<h1><?= $parametroGet ?></h1>