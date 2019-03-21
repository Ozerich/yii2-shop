<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190322_024901_seo_fields
 */
class m190322_024901_seo_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'h1_value', $this->string());
        $this->addColumn('{{%categories}}', 'seo_title', $this->string());
        $this->addColumn('{{%categories}}', 'seo_description', $this->text());

        $this->addColumn('{{%products}}', 'h1_value', $this->string());
        $this->addColumn('{{%products}}', 'seo_title', $this->string());
        $this->addColumn('{{%products}}', 'seo_description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%products}}', 'h1_value');
        $this->dropColumn('{{%products}}', 'seo_title');
        $this->dropColumn('{{%products}}', 'seo_description');

        $this->dropColumn('{{%categories}}', 'h1_value');
        $this->dropColumn('{{%categories}}', 'seo_title');
        $this->dropColumn('{{%categories}}', 'seo_description');
    }
}
