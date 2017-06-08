<?php
use Phinx\Migration\AbstractMigration;

use Pragma\ORM\Model\Change;

class AddHistoricChangesTable extends AbstractMigration
{
	/**
	 * Change Method.
	 *
	 * Write your reversible migrations using this method.
	 *
	 * More information on writing migrations is available here:
	 * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
	 *
	 * The following commands can be used in this method and Phinx will
	 * automatically reverse them when rolling back:
	 *
	 *    createTable
	 *    renameTable
	 *    addColumn
	 *    renameColumn
	 *    addIndex
	 *    addForeignKey
	 *
	 * Remember to call "create()" or "update()" and NOT "save()" when working
	 * with the Table class.
	 */
	public function change(){
		if(defined('ORM_ID_AS_UID') && ORM_ID_AS_UID){
			$strategy = defined('ORM_UID_STRATEGY') && ORM_UID_STRATEGY == 'mysql' ? 'mysql' : 'php';
			$t = $this->table(Change::getTableName(), ['id' => false, 'primary_key' => 'id']);
			switch($strategy){
				case 'mysql':
					$t->addColumn('id', 'char', ['limit' => 36])
					  ->addColumn('action_id', 'char', ['limit' => 36]);
					break;
				default:
				case 'php':
					$t->addColumn('id', 'char', ['limit' => 23])
						->addColumn('action_id', 'char', ['limit' => 23]);
					break;
			}
		}
		else{
			$t = $this->table(Change::getTableName());
			$t->addColumn('action_id', 'integer');
		}

		$t->addColumn('field', 'string')
		  ->addColumn('before', 'text')
		  ->addColumn('after', 'text')
		  ->addIndex(['action_id'])
		  ->create();
	}
}
