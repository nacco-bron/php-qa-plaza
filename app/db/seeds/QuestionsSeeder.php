<?php


use Phinx\Seed\AbstractSeed;

class QuestionsSeeder extends AbstractSeed
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
                'user_id'    => 1,
                'body'    => '普段PHPのフレームワークって何使ってますか？',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 1,
                'body'    => 'PHPにあったらいいなと思う機能ってありますか？',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 1,
                'body'    => 'みなさんはいつからPHPを書いてますか？',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('questions');
        $posts->insert($data)
              ->save();

    }
}
