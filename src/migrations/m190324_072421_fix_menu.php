<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190324_072421_fix_menu
 */
class m190324_072421_fix_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%menu_items}}', 'url', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
