<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\assets\SVSDialogAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\validators\ValidationAsset;
use yii\widgets\ActiveFormAsset;

$theme = $_COOKIE['ast_theme'] ?? 'dark';

$currentRU = $currentUS = '';
switch (\yii::$app->language) {
    case 'ru-RU':
        $currentRU = ' current';
        break;
    case 'en-US':
        $currentUS = ' current';
        break;
}

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="<?= $theme ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
                      'brandLabel' => Yii::$app->name,
                      'brandUrl'   => Yii::$app->homeUrl,
                      'brandImage' => '/ast.svg',
                      'options'    => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
                  ]);
    echo Nav::widget([
                         'options' => ['class' => 'navbar-nav'],
                         'items'   => [
                             \yii::$app->user->can('super')
                                 ? ['label' => \yii::t('app', 'Users'), 'url' => ['/conductor/user/index']] : '',
                             \yii::$app->user->can('eo')
                                 ? ['label' => \yii::t('app', 'Events'), 'url' => ['/conductor/event/index']] : '',
                             \yii::$app->user->isGuest
                                 ? ['label' => \yii::t('app', 'Login'), 'url' => ['/login']]
                                 : '<li class="nav-item">'
                                 . Html::beginForm(['/logout'])
                                 . Html::submitButton(
                                     \yii::t('app', 'Logout')
                                     . '<span>( ' . Yii::$app->user->identity->username . ' )</span>',
                                     ['class' => 'nav-link btn btn-link logout']
                                 )
                                 . Html::endForm()
                                 . '</li>'
                         ]
                     ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-12 text-right text-md-end">&copy; Сергей Сиунов <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<div class="lev lev-language">
    <div class="language ru<?= $currentRU ?>" title="<?= \yii::t('app', 'Russian language') ?>" data-toggle="tooltip"
         data-placement="left" data-lang="ru-RU"></div>
    <div class="language eng<?= $currentUS ?>" title="<?= \yii::t('app', 'English language') ?>" data-toggle="tooltip"
         data-placement="left" data-lang="en-US"></div>
</div>
<div class="lev lev-theme">
    <div class="theme dark" title="<?= \yii::t('app', 'Dark theme') ?>" data-toggle="tooltip"
         data-placement="left" data-theme="dark"></div>
    <div class="theme light" title="<?= \yii::t('app', 'Light theme') ?>" data-toggle="tooltip"
         data-placement="left" data-theme="light"></div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
