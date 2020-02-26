<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
/* @var $table string the name table */
/* @var $fields array the fields */

preg_match('/^add_(.+)_columns?_to_(.+)_table$/', $name, $matches);
$columns = $matches[1];

echo "<?php\n";
?>

use console\models\db\Migration;

/**
 * Handles adding <?= $columns ?> to table `<?= $table ?>`.
<?= $this->render('@yii/views/_foreignTables', [
     'foreignKeys' => $foreignKeys,
 ]) ?>
 */
class <?= $className ?> extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
<?= $this->render('@yii/views/_addColumns', [
    'table' => $table,
    'fields' => $fields,
    'foreignKeys' => $foreignKeys,
])
?>
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
<?= $this->render('@yii/views/_dropColumns', [
    'table' => $table,
    'fields' => $fields,
    'foreignKeys' => $foreignKeys,
])
?>
    }
}
