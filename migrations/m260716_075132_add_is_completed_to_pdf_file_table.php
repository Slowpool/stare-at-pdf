<?php

use yii\db\Migration;

/**
 * Class m260716_075132_add_is_completed_to_pdf_file_table
 */
class m260716_075132_add_is_completed_to_pdf_file_table extends Migration
{
    public function up()
    {
        $this->addColumn("pdf_file", "is_completed", $this->boolean() . " NOT NULL DEFAULT FALSE");
    }

    public function down()
    {
        $this->dropColumn("pdf_file", "is_completed");
    }
}
