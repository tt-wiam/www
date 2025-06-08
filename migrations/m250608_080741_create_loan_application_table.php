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
     * @throws \yii\db\Exception
     */
    public function safeUp(): void
    {
        $this->db->createCommand("CREATE TYPE loan_statuses AS ENUM ('approved', 'declined');")->execute();

        $this->createTable('{{%loan_application}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'status' => "loan_statuses",
            'optimistic_lock' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%loan_application}}');
        $this->db->createCommand("DROP TYPE loan_statuses")->execute();
    }
}
