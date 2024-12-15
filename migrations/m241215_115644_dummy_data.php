<?php

use yii\db\Migration;

/**
 * Class m241215_115644_dummy_data
 */
class m241215_115644_dummy_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pdf_file', 'name', 'VARCHAR(150) NOT NULL');
        $this->createIndex('uq_pdf_file', 'pdf_file', ['name', 'user_id'], true);
        $this->execute("insert into user values (1, 'admin', 'd6293a1eb09b09063261b11a84f404bc79440d7259389711114c1d902018c060', 'acAdmin', 'auAdmin'), (2, 'john', '03ac26e98b562753f9198b0f1a31c30e8b2b6cde8c1baea74a61e4a7db62c0e7', 'acJohn', 'auJohn');");
        $this->execute("insert into pdf_file values (1, 'EFC eng', 150, 1), (2, 'EFC eng', 150, 2), (3, 'semaphores', 150, 1), (4, 'semaphores', 150, 2)");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241215_115644_dummy_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241215_115644_dummy_data cannot be reverted.\n";

        return false;
    }
    */
}
