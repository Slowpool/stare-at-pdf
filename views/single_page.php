<?php

/** @var yii\web\View $this */
/** @var app\models\PageModel $page */

$this->title = $page->selected_nav;
?>

<div id="page-content">
    <?= $page->content ?>
</div>