<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
// pdf viewer requires it
yii\widgets\ActiveFormAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title id="title"><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body id="body" class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'brandOptions' => ['class' => 'ajax-action'],
            'options' => ['id' => 'main-navbar', 'class' => 'navbar-expand-md navbar-dark bg-dark']
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                Html::beginTag('li', ['class' => 'nav-item'])
                . Html::a('Library', '/library', ['class' => 'ajax-action nav-link']),
                // login or logout
                Html::beginTag('li', ['id' => 'identity-action-container', 'class' => 'nav-item'])
                . Html::endTag('li'),
            ]
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <?= Alert::widget() ?>
    </main>

    <?php $this->endBody() ?>
    <?php $this->registerJsFile('/js/main.js') ?>
</body>

</html>
<?php $this->endPage() ?>