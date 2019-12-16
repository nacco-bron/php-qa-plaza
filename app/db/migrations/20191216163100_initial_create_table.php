<?php

use Phinx\Migration\AbstractMigration;

class InitialCreateTable extends AbstractMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $answers = $this->table('answers', ['signed' => false, 'collation' => 'utf8mb4_general_ci']);
        $answers->addColumn('question_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false])
                ->addColumn('user_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false])
                ->addColumn('body', 'string', ['limit' => 255, 'null' => false])
                ->addTimestamps()
                ->create();

        $questions = $this->table('questions', ['signed' => false, 'collation' => 'utf8mb4_general_ci']);
        $questions->addColumn('user_id', 'integer', ['limit' => 10, 'signed' => false, 'null' => false])
                ->addColumn('body', 'string', ['limit' => 255, 'null' => false])
                ->addTimestamps()
                ->create();

        $users = $this->table('users', ['signed' => false, 'collation' => 'utf8mb4_general_ci']);
        $users->addColumn('username', 'string', ['limit' => 16, 'null' => false])
                ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
                ->addColumn('nickname', 'string', ['limit' => 32, 'null' => false])
                ->addTimestamps()
                ->addIndex(['username'], ['unique' => true])
                ->create();

    }
}
