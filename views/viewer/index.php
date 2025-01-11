<?php

/** @var yii\web\View $this */
/** @param PdfModel $pdfModel  */

use app\models\viewer\PdfModel;
use diecoding\pdfjs\PdfJs;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\UserUploadsPathHelper;

?>
<div class="pdf-viewer-container">
    <?php if ($pdfModel == null): ?>
        <h3>
            You don't have any <strong>PDF</strong> in your
            <?= Html::a('library', Url::to('library'), ['class' => 'ajax-action']); ?>
            .
        </h3>
    <?php else: ?>
        <nav id="custom-toolbar">
            <ul class="navbar-nav nav">
                <li id="update-bookmark-container">
                    <?= $this->render(Yii::getAlias('@partial_new_bookmark_form'), ['pdfId' => $pdfModel->id]) ?>
                </li>
            </ul>
        </nav>
        <?php $pdfUrl = $pdfModel->getPdfSpecified() ? Url::to([UserUploadsPathHelper::toFile($pdfModel->name, true), '#' => "page=$pdfModel->bookmark"]) : ''; ?>
        <?= PdfJs::widget([
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
    <?php endif ?>
</div>