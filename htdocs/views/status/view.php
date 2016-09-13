<?php
  use yii\helpers\Html;
?>

<h1>Результаты импорта</h1>
<p><label>Загрузка файла</label>:</p>
<?php
echo $model->upload($model->uploadFile);

?><br /><br />
<p><label>Импорт в базу</label>:</p>
<?php
echo $model->getPermissionsLabel($model->permissions);
echo $model->xml2db('uploads/'.$model->xml);
$model->delete_upload();


?>
