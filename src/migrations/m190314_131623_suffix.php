<?php

use yii\db\Migration;

/**
 * Class m190314_131623_suffix
 */
class m190314_131623_suffix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%fields}}', 'value_suffix', $this->string());
        $this->addColumn('{{%fields}}', 'value_prefix', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%fields}}', 'value_suffix');
        $this->dropColumn('{{%fields}}', 'value_prefix');
    }
}
