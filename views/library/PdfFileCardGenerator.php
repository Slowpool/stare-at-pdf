<?php 

namespace app\views\library;

use yii\bootstrap5\Html;

class PdfFileCardGenerator {
    // ops. id is not neccessary here because it's offline app.
    public static function render($id, $fileName, $bookmark) {
        // TODO redo via ob
        $result = Html::beginTag('li', ['class' => 'pdf-file-card']);
        $result .= Html::a(Html::encode(str_replace('.pdf', '', $fileName)), '/stare-at/' . Html::encode($fileName) . '?page=' . Html::encode($bookmark), ['class' => 'pdf-file-link ajax-action']);
        $result .= " " . Html::encode($bookmark) . " p.";
        $result .= Html::endTag('li');
        return $result;
    }
}