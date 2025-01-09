<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%pdf_file}}`.
 */
class m250109_091045_add_slug_column_to_pdf_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pdf_file}}', 'slug', $this->string(150)->notNull());

        $this->execte(
        'CREATE FUNCTION SLUGIFICATE(`name` VARCHAR(150))
        RETURNS VARCHAR(150)
        NO SQL
        DETERMINISTIC
        SQL SECURITY INVOKER
        BEGIN
            DECLARE i INT DEFAULT 1;
            DECLARE cur_character CHAR(1);
            DECLARE slug VARCHAR(150) DEFAULT "";
            DECLARE normalized_name VARCHAR(150);

            SET normalized_name = TRIM(`name`); # TODO it could be more complex, like treating character sequence "a___ _ __b" as "a-b"
            WHILE i <= LENGTH(`name`) DO
                SET cur_character = SUBSTRING(`name`, i, 1);
                SET slug = slug + 
                    IF(cur_character REGEXP "[a-zA-Z0-9]", # it is a letter
                    /* THEN */ cur_character,
                    /* ELSE */ "-"
                    );
                SET i = i + 1;
            END WHILE;
            RETURN slug;
        END;
        ');
        $this->update('{{%pdf_file}}', ['slug' => 'SLUGIFICATE(name)']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pdf_file}}', 'slug');
    }
}
