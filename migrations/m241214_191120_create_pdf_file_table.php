<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pdf_file}}`.
 */
class m241214_191120_create_pdf_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pdf_file}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull()->unique(),
            'bookmark' => $this->integer()->defaultValue(1),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pdf_file}}');
    }
}
