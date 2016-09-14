<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'Недвижимость Экономика Управление',
	'name' => 'Недвижимость Экономика Управление',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'language' => 'ru-RU', 
    'components' => [

		'i18n' => [  // язык
			'translations' => [
				'*' => [
					'class' => 'yii\i18n\DbMessageSource',
					'sourceLanguage' => 'ru',
						],
					],
			],
		'messages' => array(
        'class'                  => 'CDbMessageSource',
        'cacheID'                => 'cache',
        'cachingDuration'        => 43200, // 12 hours
        'connectionID'           => 'db',
        'sourceMessageTable'     => 'vest_source_message',
        'translatedMessageTable' => 'vest_message',
    ),
			
			
        'request' => [
			'class' => 'app\frontend\components\LangRequest',
            'cookieValidationKey' => 'QHNk1rtAsrvAr3GKgnbe-dSDYoeDeb_N',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
       'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
		'transport' => [
			'class' => 'Swift_SmtpTransport',
			'host' => 'smtp.yandex.ru',
			'username' => 'vestnik@bez-sso.ru',
			'password' => 'vestnik123',
			'port' => '587',
			'encryption' => 'tls',
			],
            'useFileTransport' => false,
			'htmlLayout'=>'layouts/html',
            'textLayout'=>false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'assetManager'=>[
        'class'=>'yii\web\AssetManager',
        'linkAssets'=>false,
		],

        'db' => require(__DIR__ . '/db.php'),

		'user' => [
			'identityClass' => 'budyaga\users\models\User',
			'enableAutoLogin' => true,
			'loginUrl' => ['/login'],
		],
		'authClientCollection' => [
			'class' => 'yii\authclient\Collection',
/*			'clients' => [
				'vkontakte' => [
					'class' => 'budyaga\users\components\oauth\VKontakte',
					'clientId' => 'XXX',
					'clientSecret' => 'XXX',
					'scope' => 'email'
				],
				'google' => [
					'class' => 'budyaga\users\components\oauth\Google',
					'clientId' => 'XXX',
					'clientSecret' => 'XXX',
				],
				'facebook' => [
					'class' => 'budyaga\users\components\oauth\Facebook',
					'clientId' => 'XXX',
					'clientSecret' => 'XXX',
				],
				'github' => [
					'class' => 'budyaga\users\components\oauth\GitHub',
					'clientId' => 'XXX',
					'clientSecret' => 'XXX',
					'scope' => 'user:email, user'
				],
				'linkedin' => [
					'class' => 'budyaga\users\components\oauth\LinkedIn',
					'clientId' => 'XXX',
					'clientSecret' => 'XXX',
				],
				'live' => [
					'class' => 'budyaga\users\components\oauth\Live',
					'clientId' => 'XXX',
					'clientSecret' => 'XXX',
				],
				'yandex' => [
					'class' => 'budyaga\users\components\oauth\Yandex',
					'clientId' => 'XXX',
					'clientSecret' => 'XXX',
				],
				'twitter' => [
					'class' => 'budyaga\users\components\oauth\Twitter',
					'consumerKey' => 'XXX',
					'consumerSecret' => 'XXX',
				],
			],
*/		],
		
	
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'class'=>'app\frontend\components\LangUrlManager', // язык
			'rules' => [
    			'/' => '/site/index',			// языковые правила
//				'<controller:\w+>/<action:\w+>/*'=>'<controller>/<action>',
				'/signup' => '/user/user/signup',   // для модуля Users
				'/login/' => '/user/user/login',
				'/site/login/' => '/user/user/login/',
				'/logout' => '/user/user/logout',
				'/requestPasswordReset' => '/user/user/request-password-reset',
				'/resetPassword' => '/user/user/reset-password',
				'/profile' => '/user/user/profile',
				'/retryConfirmEmail' => '/user/user/retry-confirm-email',
				'/confirmEmail' => '/user/user/confirm-email',
				'/unbind/<id:[\w\-]+>' => '/user/auth/unbind',
				'/oauth/<authclient:[\w\-]+>' => '/user/auth/index',  

				'/about' => '/site/about', // страницы - TODO вынести в htaccess ?
				'/authors' => '/site/authors',
				'/listissue' => '/site/listissue',
				'/lastissue' => '/site/lastissue',
			],	
		],
		'authManager' => [   // users
        'class' => 'yii\rbac\DbManager',
		],
		
    ],  // компоненты

	'modules' => [


		'user' => [
			'class' => 'budyaga\users\Module',
			'userPhotoUrl' => 'http://example.com/uploads/user/photo', // ПОменять
			'userPhotoPath' => '@frontend/web/uploads/user/photo'
		],
			
		'pages' => [
            'class' => 'bupy7\pages\Module',
			'tableName' => 'vest_pages',
			'tableNameTranslate' => 'vest_source_message',
			'tableNameTransMsg' => 'vest_message',
            'controllerMap' => [
                'manager' => [
                    'class' => 'bupy7\pages\controllers\ManagerController',
                    'as access' => [
                        'class' => yii\filters\AccessControl::className(),
                        'ruleConfig' => [
                            'class' => yii\filters\AccessRule::className(),
                        ],
                        'rules' => [
                            [
								'allow' => true,
                                'roles' => ['@']
							],
                        ],
                    ],
                ],
            ],
			'pathToImages' => '@webroot/images',
            'urlToImages' => '@web/images',
            'pathToFiles' => '@webroot/files',
            'urlToFiles' => '@web/files',
            'uploadImage' => true,
            'uploadFile' => true,
            'addImage' => true,
            'addFile' => true,		
		],
	], // модули
	
	// ******************************** 
	'params' => $params,

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
		'allowedIPs' => ['127.0.0.1', '::1', '77.74.28.*', '192.168.178.20','134.0.101.174']
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
		'allowedIPs' => ['127.0.0.1', '::1', '77.74.28.*', '192.168.178.20','134.0.101.174'] // регулируйте в соответствии со своими нуждами
    ];
}

return $config;
