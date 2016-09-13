<?php
  use yii\helpers\Html;
  use yii\widgets\ActiveForm;
  use app\models\Status;
?>

<?php
//$form = ActiveForm::begin(); //Default Active Form begin
$form = ActiveForm::begin([
    'id' => 'active-form',
    'options' => [
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
				],
])
/* ADD FORM FIELDS */
?>
	<?= $form->field($model, 'uploadFile')->fileInput() ?>
    <?= $form->field($model, 'permissions')->dropDownList($model->getPermissions(), ['prompt'=>'Выберите тип файла']) ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
