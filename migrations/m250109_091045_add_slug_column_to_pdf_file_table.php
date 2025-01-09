<?php

use yii\db\Migration;
use yii\db\Expression;

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
        // the workaroundest workaround
        $this->safeDown();
        // $transaction = $this->db->beginTransaction();
        // try {

            $this->addColumn('{{%pdf_file}}', 'slug', $this->string(150)->notNull());

            $db = $this->db;
            $quotedName = $db->quoteColumnName('name');
            $this->execute(
                "CREATE FUNCTION SLUGIFICATE($quotedName VARCHAR(150))
                RETURNS VARCHAR(150)
                NO SQL
                DETERMINISTIC
                SQL SECURITY INVOKER
                BEGIN
                    DECLARE i INT DEFAULT 1;
                    DECLARE cur_character CHAR(1);
                    DECLARE slug VARCHAR(150) DEFAULT '';
                    DECLARE normalized_name VARCHAR(150);
                    DECLARE previous_was_character BOOL DEFAULT TRUE;

                    SET normalized_name = TRIM($quotedName);
                    WHILE i <= LENGTH(normalized_name) DO
                        SET cur_character = SUBSTRING(normalized_name, i, 1);
                        IF cur_character REGEXP '[a-zA-Z0-9]' THEN
                            BEGIN
                                SET slug = CONCAT(slug, cur_character);
                                SET previous_was_character = FALSE;
                            END;
                        ELSEIF previous_was_character = FALSE THEN
                            BEGIN
                                SET slug = CONCAT(slug, '-');
                                SET previous_was_character = TRUE;
                            END;
                        /* when it is not a alphanumeric and previous_character = TRUE then skip */
                        END IF;
                        SET i = i + 1;
                    END WHILE;
                    RETURN slug;
                END;
                "
            );
            $this->update('{{%pdf_file}}', ['slug' => new Expression("SLUGIFICATE($quotedName)")]);
            $this->createIndex(
                '{{%unidx-user_id-slug}}',
                '{{%pdf_file}}',
                ['user_id', 'slug'],
                true,
            );
        //     $transaction->commit();
        // } catch (Exception $exception) {
        //     $transaction->rollBack();
        //     throw $exception;
        // }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // IF EXISTS workaround
        try {
            $this->dropIndex('{{%unidx-user_id-slug}}', '{{%pdf_file}}');
        } catch (Exception) {
        }
        try {
            $this->dropColumn('{{%pdf_file}}', 'slug');
        } catch (Exception) {
        }
        $this->execute('DROP FUNCTION IF EXISTS SLUGIFICATE;');
    }
}
