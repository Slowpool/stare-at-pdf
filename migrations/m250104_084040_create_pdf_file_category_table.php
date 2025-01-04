<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pdf_file_category}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m250104_084040_create_pdf_file_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pdf_file_category}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(50)->notNull(),
            'color' => $this->char(6)->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-pdf_file_category-user_id}}',
            '{{%pdf_file_category}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-pdf_file_category-user_id}}',
            '{{%pdf_file_category}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%unidx-pdf_file_category-user_id-name}}',
            '{{%pdf_file_category}}',
            ['user_id', 'name'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-pdf_file_category-user_id}}',
            '{{%pdf_file_category}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-pdf_file_category-user_id}}',
            '{{%pdf_file_category}}'
        );

        $this->dropTable('{{%pdf_file_category}}');
    }
}
