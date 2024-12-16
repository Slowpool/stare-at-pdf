<?php 

namespace app\views\library;

use yii\bootstrap5\Html;

class PdfFileCardGenerator {
    // ops. id is not neccessary here because it's offline app.
    public static function generate($id, $name, $bookmark) {
        $result = Html::beginTag('li', ['class' => 'pdf-file-card']);
        $result .= Html::a(Html::encode($name), '/stare-at/' . Html::encode($name), ['class' => 'pdf-file-link']);
        $result .= " " . Html::encode($bookmark) . " p.";
        $result .= Html::endTag('li');
        return $result;
    }
}