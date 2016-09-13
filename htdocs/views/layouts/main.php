<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\frontend\widgets\WLang;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?> 	
    <title><?= Html::encode( Yii::t('main','site_name') ) ?></title>
    <?php $this->head() ?>

</head>
<body>

<?php $this->beginBody() ?>
<header>
	<div class="header">
		<div class="logo"><?= Yii::t('html','header') ?></div>
		<div class="lang"><?= WLang::widget();?></div><br/>
	</div>
</header>
<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::t('main','site_name'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
// формируем пункты меню	
if (Yii::$app->user->isGuest) {
		$Navbar[] = ['label' => 'Главная', 'url' => ['/']];
		$Navbar[] = ['label' => 'Архив', 'items' => [ // первый уровень
						['label' => 'Свежий выпуск', 'url' => (['/lastissue'])],
                                                ['label' => 'Список выпусков', 'url' => (['/listissue'])], // второй уровень
						
						],
					];
		$Navbar[] = ['label' => 'Материалы', 'items' => [ // первый уровень
						['label' => 'О журнале','items' => [  // второй уровень
							['label' => 'Цели и задачи, тематика', 'url' => (['/about'])], // третий уровень
							['label' => 'Язык публикаций', 'url' => (['/about'])],
							['label' => 'Редакционная политика', 'url' => (['/about'])],
							['label' => 'Индексирование', 'url' => (['/about'])],
							['label' => 'Редколегия. Редсовет', 'url' => (['/about'])],
							['label' => 'Рецензирование', 'url' => (['/about'])],
							],
						],							
						['label' => 'Авторам', 'url' => (['/authors'])],
						['label' => 'Подписка', 'url' => (['/authors'])],
						['label' => 'Контакты', 'url' => (['/contacts'])],
						['label' => 'Предоставить статью в редакцию', 'items' => [
							['label' => 'email', 'url'=>(['mailto://submission@n-eu.ru']),],
							],
						],
					],
				];
        $Navbar[] =  ['label' => 'Войти', 'url' => ['/site/login']];
} else {
		$Navbar[] = ['label' => 'Главная', 'url' => ['/']];
		$Navbar[] = ['label' => 'Архив', 'items' => [ // первый уровень
						['label' => 'Список выпусков', 'url' => (['/listissue'])], // второй уровень
						['label' => '!Свежий выпуск', 'url' => (['/lastissue'])],
						],
					];
					
					
		$Navbar[] = ['label' => 'Материалы',
						'url' => ['/about'],
						'items' => [ // первый уровень
						['label' => 'О журнале',
							'url' => ['/about'],
						'items' => [  // второй уровень
							['label' => 'Цели и задачи, тематика', 'url' => (['/about#aim'])], // третий уровень
							['label' => 'Язык публикаций', 'url' => (['/about'])],
							['label' => 'Редакционная политика', 'url' => (['/about#politica'])],
							['label' => 'Индексирование', 'url' => (['/about'])],
							['label' => 'Редколегия. Редсовет', 'url' => (['/about#redcolegia'])],
							['label' => 'Рецензирование', 'url' => (['/about#recenz'])],
							],
						],							
						['label' => 'Авторам', 'url' => (['/authors'])],
						['label' => 'Подписка', 'url' => (['/authors'])],
						['label' => 'Контакты', 'url' => (['/contacts'])],
						['label' => 'Предоставить статью в редакцию', 'items' => [
							['label' => 'Open Journal System', 'url' => (['/status/create'])],
							['label' => 'email', 'url' => (['/test'])],
							],
						],
					],
				];
        $Navbar[] = [
            'label' =>  Html::encode(Yii::$app->user->identity->username) ,
            'items' => [
                ['label' => 'Профиль', 'url' => '/profile'],
                '<li class="divider"></li>',
                ['label' => 'Выйти', 'url' => '/site/logout', 'linkOptions' => ['data-method' => 'post']]
            ], 'url' => ['#'],

        ];
    }
			
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $Navbar,
		]);
    NavBar::end();
    ?>

	
    <?php 
if (!Yii::$app->user->isGuest) {
 // пользовательское меню
    NavBar::begin([
        'brandLabel' => Yii::t('menu','пользовательское меню'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Загрузить выпуск', 'url' => ['/status/create']],
            ['label' => 'Кнопка управления', 'url' => ['/site/about']],
            ['label' => 'Сверх секретная', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Войти', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
	    NavBar::end();
}
    ?>
 <div class="messages">
 </div>
	
    <div class="container">
		<div class="main_conteiner">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>

		</div>
		<div class="banners">
		logo важных дядек

		</div>
    </div>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?php echo Yii::t('main','site_name'); ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
	<div class="container"><br/>
        <p class="pull-left">Лого партнеров</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
