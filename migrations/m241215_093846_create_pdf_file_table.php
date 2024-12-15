<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pdf_file}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m241215_093846_create_pdf_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pdf_file}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(150)->notNull()->unique(),
            'bookmark' => $this->integer()->notNull()->defaultValue(1),
            'user_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-pdf_file-user_id}}',
            '{{%pdf_file}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-pdf_file-user_id}}',
            '{{%pdf_file}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-pdf_file-user_id}}',
            '{{%pdf_file}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-pdf_file-user_id}}',
            '{{%pdf_file}}'
        );

        $this->dropTable('{{%pdf_file}}');
    }
}
