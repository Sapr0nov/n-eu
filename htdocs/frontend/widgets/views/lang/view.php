<?php
use yii\helpers\Html;
?>
<div id="lang">
    <span id="current-lang">
        <img src="/img/header/<?= $current->name ?>.jpg">
    </span>
    <span id="other-lang">
        <?php foreach ($langs as $lang):?>
                <?= Html::a("<img src=\"/img/header/".$lang->name."_grey.jpg\">", '/'.$lang->url.Yii::$app->getRequest()->getLangUrl()) ?>
        <?php endforeach;?>
    </span>
</div>
