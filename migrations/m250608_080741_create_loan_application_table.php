<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

/** @noinspection PhpUnused */

use yii\db\Migration;

/**
 * Handles the creation of table `{{%loan_application}}`.
 */
class m250608_080741_create_loan_application_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%loan_application}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term ' => $this->integer()->notNull(),
            'status' => "ENUM('approved', 'declined')",
            'optimistic_lock' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%loan_application}}');
    }
}
