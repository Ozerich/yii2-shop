<?php

namespace ozerich\shop\migrations;

use yii\db\Migration;

/**
 * Class m190609_084200_category_seo
 */
class m190609_084200_category_seo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%categories}}', 'seo_title_products_template', $this->string(1000));
        $this->addColumn('{{%categories}}', 'seo_description_products_template', $this->string(1000));

        $this->alterColumn('{{%products}}', 'is_price_from', $this->boolean()->notNull()->defaultValue(false));
        $this->update('{{%products}}', ['is_price_from' => 0], 'type=:type', [':type' => 'MODULAR']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%products}}', 'is_price_from', $this->boolean()->notNull()->defaultValue(true));

        $this->dropColumn('{{%categories}}', 'seo_title_products_template');
        $this->dropColumn('{{%categories}}', 'seo_description_products_template');
    }
}
