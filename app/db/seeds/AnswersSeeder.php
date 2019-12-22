<?php


use Phinx\Seed\AbstractSeed;

class AnswersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'question_id'    => 3,
                'user_id'    => 1,
                'body'    => '私はLaravelです！あとたまにYiiを使っています！',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'question_id'    => 3,
                'user_id'    => 1,
                'body'    => '僕はCakePHP！',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('answers');
        $posts->insert($data)
              ->save();

    }
}
