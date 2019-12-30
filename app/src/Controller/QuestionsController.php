<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Questions Controller
 *
 * @property \App\Model\Table\QuestionsTable $Questions
 *
 * @method \App\Model\Entity\Question[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QuestionsController extends AppController
{
    /**
     * @inheritdoc
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Answers');
    }

    /**
     * 質問一覧画面
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $questions = $this->paginate(
            $this->Questions->findQuestionsWithAnsweredCount(),
            ['order' => ['Questions.id' => 'DESC' ]]
        );

        $this->set(compact('questions'));
    }

    /**
     * 質問詳細画面
     *
     * @param string|null $id 質問ID
     * @return void
     */
    public function view(int $id)
    {
        $question = $this->Questions->get($id);

        $answers = $this
                    ->Answers
                    ->find()
                    ->where(['Answers.question_id' => $id])
                    ->orderAsc('Answers.id')
                    ->all();

        $this->set(compact('question', 'answers'));
    }
  
    /**
     * 質問投稿画面/質問投稿処理
     *
     * @return \Cake\Http\Response|null 質問投稿後に質問一覧画面へ繊維する
     */
    public function add()
    {
        $question = $this->Questions->newEntity();
        if ($this->request->is('post')) {
            $question = $this->Questions->patchEntity($question, $this->request->getData());
            $question->user_id = 1; // @TODO ユーザ管理機能実装時に修正する
            if ($this->Questions->save($question)) {
                $this->Flash->success('質問を投稿しました');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('質問の投稿に失敗しました');
        }
        $this->set(compact('question'));
    }

    /**
     * Delete method
     *
     * @param string|null $id 質問ID
     * @return \Cake\Http\Response|null 回答削除後に質問詳細画面に遷移する
     */
    public function delete(int $id)
    {
        $this->request->allowMethod(['post']);

        $question = $this->Questions->get($id);
        // @TODO 質問を削除できるのは質問投稿者のみとする

        if ($this->Questions->delete($question)) {
            $this->Flash->success('質問を削除しました');
        } else {
            $this->Flash->error('質問の削除に失敗しました');
        }

        return $this->redirect(['action' => 'index']);
    }
}
