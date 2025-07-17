<?php
return [
    'id' => 'lrsinfo-app',
    // the basePath of the application will be the `micro-app` directory
    'basePath' => __DIR__,
    // this is where the application will find all controllers
    'controllerNamespace' => 'micro\controllers',
    // set an alias to enable autoloading of classes from the 'micro' namespace
    'aliases' => [
        '@micro' => __DIR__,
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
   
    'bootstrap' => ['gii'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '8RwTKp84nvCKq1zc8cw5SJuBSTb0LXfKPRA',
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'lrs_queue',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],
        'user' => [
            'identityClass' => 'micro\models\User',
            'enableAutoLogin' => true,
        ],
       
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=lrsinfo',
            'username' => 'root',
            'password' => 'mysql',
            'charset' => 'utf8',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',           
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false, 
            'showScriptName' => false,
      ],
    ],
    'modules' => [
        
        'gii' => [
            'class' => 'yii\gii\Module',
           // 'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20', '172.16.0.0/12'],
            'allowedIPs' => ['*'], 
        ]
    ]
];
