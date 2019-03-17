<?php

use yii\db\Migration;

/**
 * Class m190313_172850_fix
 */
class m190313_172850_fix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%field_groups}}', 'image_id', $this->integer());
        $this->alterColumn('{{%field_groups}}', 'name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
