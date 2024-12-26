<?php

/** @var yii\web\View $this */
/** @var $bookmark must not be null */

use diecoding\pdfjs\PdfJs;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\UserUploadsPathMaker;

?>
<div class="pdf-viewer-container">
    <nav id="custom-toolbar">
        <ul class="navbar-nav nav">
            <li id="update-bookmark-container">
                <?= $this->render(Yii::getAlias('@partial_new_bookmark_form'), compact('pdfName')) ?>
            </li>
        </ul>
    </nav>

    <?php
    $pdfUrl = $pdfSpecified ? Url::to([UserUploadsPathMaker::toFile($pdfName, true), '#' => "page=$bookmark"]) : '';
    echo PdfJs::widget([
        'url' => $pdfUrl,
        // 'encodeUrl' => false,
        'options' => [
            'id' => 'pdf-viewer-widget',
            'style' => [
                'width' => '100%',
                'height' => '100%',
            ],
            // // this doesn't work, but supposed to
            // 'enableClientValidation' => false,
            // 'openWithFragmentIdentifier' => 'true',
            // 'disableAutoFetch' => 'true',
        ],
        'sections' => [
            'viewLayers' => false,
            'viewAttachments' => false,
        ]
    ]); ?>
</div>