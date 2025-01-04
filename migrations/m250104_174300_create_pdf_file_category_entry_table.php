<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pdf_file_category_entry}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pdf_file}}`
 * - `{{%pdf_file_category}}`
 */
class m250104_174300_create_pdf_file_category_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pdf_file_category_entry}}', [
            'id' => $this->primaryKey(),
            'pdf_file_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `pdf_file_id`
        $this->createIndex(
            '{{%idx-pdf_file_category_entry-pdf_file_id}}',
            '{{%pdf_file_category_entry}}',
            'pdf_file_id'
        );

        // add foreign key for table `{{%pdf_file}}`
        $this->addForeignKey(
            '{{%fk-pdf_file_category_entry-pdf_file_id}}',
            '{{%pdf_file_category_entry}}',
            'pdf_file_id',
            '{{%pdf_file}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-pdf_file_category_entry-category_id}}',
            '{{%pdf_file_category_entry}}',
            'category_id'
        );

        // add foreign key for table `{{%pdf_file_category}}`
        $this->addForeignKey(
            '{{%fk-pdf_file_category_entry-category_id}}',
            '{{%pdf_file_category_entry}}',
            'category_id',
            '{{%pdf_file_category}}',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            '{{%unidx-pdf_file_category_entry-category_id}}',
            '{{%pdf_file_category_entry}}',
            ['category_id', 'pdf_file_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pdf_file}}`
        $this->dropForeignKey(
            '{{%fk-pdf_file_category_entry-pdf_file_id}}',
            '{{%pdf_file_category_entry}}'
        );

        // drops index for column `pdf_file_id`
        $this->dropIndex(
            '{{%idx-pdf_file_category_entry-pdf_file_id}}',
            '{{%pdf_file_category_entry}}'
        );

        // drops foreign key for table `{{%pdf_file_category}}`
        $this->dropForeignKey(
            '{{%fk-pdf_file_category_entry-category_id}}',
            '{{%pdf_file_category_entry}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-pdf_file_category_entry-category_id}}',
            '{{%pdf_file_category_entry}}'
        );

        $this->dropTable('{{%pdf_file_category_entry}}');
    }
}
