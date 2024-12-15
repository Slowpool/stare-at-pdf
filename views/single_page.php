<?php

/** @var yii\web\View $this */
/** @var app\models\PageModel $page */

$this->title = $page->selectedNav;
?>

<div id="page-content">
    <?= $page->content ?>
</div>