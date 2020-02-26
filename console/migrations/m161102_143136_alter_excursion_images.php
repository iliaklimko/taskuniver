<?php

use console\models\db\Migration;

class m161102_143136_alter_excursion_images extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%excursion_image}}', 'main');
        $this->addColumn('{{%excursion}}', 'featured_image', $this->string()->notNull());
    }

    public function down()
    {
        $this->addColumn('{{%excursion_image}}', 'main', $this->boolean()->notNull()->defaultValue(false));
        $this->dropColumn('{{%excursion}}', 'featured_image');
    }
}
