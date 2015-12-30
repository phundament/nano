<?php

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$this->title = $this->title.' - '.Yii::$app->params['appName'];

switch (Yii::$app->settings->get('registerPrototypeAsset', 'app.assets')) {
    case true:
        \app\modules\prototype\assets\DbAsset::register($this);
        break;
    case null:
        Yii::$app->settings->set('registerPrototypeAsset', true, 'app.assets');
        Yii::$app->settings->deactivate('registerPrototypeAsset', 'app.assets');
    case false:
        AppAsset::register($this);
}

rmrevin\yii\fontawesome\AssetBundle::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?= \app\modules\prototype\widgets\HtmlWidget::widget(['key' => 'head']) ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php
    NavBar::begin(
        [
            'brandLabel' => getenv('APP_TITLE'),
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-fixed-top',
            ],
        ]
    );
    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav'],
            'encodeLabels' => false,
            'items' => \dmstr\modules\pages\models\Tree::getMenuItems('root_'.Yii::$app->language),
        ]
    );
    $menuItems = [];
    $menuItems[] = [
        'label' => '<i class="fa fa-github-alt"></i> GitHub',
        'url' => 'https://github.com/phundament/app',
        'visible' => true
    ];

    if (Yii::$app->hasModule('user')) {
        if (Yii::$app->user->isGuest) {
            #$menuItems[] = ['label' => 'Signup', 'url' => ['/user/registration/register']];
            $menuItems[] = ['label' => 'Login', 'url' => ['/user/security/login']];
        } else {
            $menuItems[] = [
                'label' => '<i class="glyphicon glyphicon-user"></i> '.Yii::$app->user->identity->username,
                'options' => ['id' => 'link-user-menu'],
                'items' => [
                    [
                        'label' => '<i class="glyphicon glyphicon-user"></i> Profile',
                        'url' => ['/user/profile/show', 'id' => \Yii::$app->user->id],
                    ],
                    '<li class="divider"></li>',
                    [
                        'label' => '<i class="glyphicon glyphicon-log-out"></i> Logout',
                        'url' => ['/user/security/logout'],
                        'linkOptions' => ['data-method' => 'post', 'id' => 'link-logout'],
                    ],
                ],
            ];
            $menuItems[] = [
                'label' => '<i class="glyphicon glyphicon-cog"></i>',
                'url' => ['/backend'],
                'visible' => Yii::$app->user->can(
                        'backend_default'
                    ) || (isset(Yii::$app->user->identity) && Yii::$app->user->identity->isAdmin),
            ];
        }
    }
    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $menuItems,
        ]
    );
    NavBar::end();
    ?>

    <div class="alert-wrapper"><?= Alert::widget() ?></div>

    <?= $content ?>
</div>

<footer class="footer">
    <?= \app\modules\prototype\widgets\HtmlWidget::widget(['key' => 'footer']) ?>
    <div class="container">
        <p class="pull-right">
            <span class="label label-default"><?= YII_ENV ?></span>
        </p>
        <p class="pull-left">
            <?= Html::a(
                Html::img('http://t.phundament.com/p4-16-bw.png', ['alt' => 'Icon Phundament 4']),
                '#',
                ['data-toggle' => 'modal', 'data-target' => '#infoModal']
            ) ?>
        </p>
    </div>
</footer>

<!-- Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <?= $this->render('_modal') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
