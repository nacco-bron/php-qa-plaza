<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Answer;

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
            $this->Questions->findQuestionsWithAnsweredCount()->contain(['Users']),
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
        $newAnswer = $this->Answers->newEntity([
            'question_id' => $id
        ]);
        $this->viewRendering($id, $newAnswer);
    }
  
    private function viewRendering(int $id, Answer $newAnswer)
    {
        $question = $this->Questions->get($id, ['contain' => ['Users']]);
        $answers = $this
                    ->Answers
                    ->find()
                    ->where(['Answers.question_id' => $id])
                    ->contain(['Users'])
                    ->orderAsc('Answers.id')
                    ->all();

        $this->set(compact('question', 'answers', 'newAnswer'));
        $this->render('/Questions/view');
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
            $question->user_id = $this->Auth->user('id');
            if ($this->Questions->save($question)) {
                $this->Flash->success('質問を投稿しました');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('質問の投稿に失敗しました');
        }
        $this->set(compact('question'));
    }

    /**
     * 質問削除処理
     *
     * @param string|null $id 質問ID
     * @return \Cake\Http\Response|null 回答削除後に質問詳細画面に遷移する
     */
    public function delete(int $id)
    {
        $this->request->allowMethod(['post']);

        $question = $this->Questions->get($id);
        if ($question->user_id != $this->Auth->user('id')) {
            $this->Flash->error('他のユーザーの質問を削除することはできません');
            return $this->redirect(['action' => 'index']);
        }

        if ($this->Questions->delete($question)) {
            $this->Flash->success('質問を削除しました');
        } else {
            $this->Flash->error('質問の削除に失敗しました');
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * 回答投稿処理
     *
     * @return \Cake\Http\Response|null 解答投稿後に質問詳細画面に遷移する
     */
    public function addAnswer()
    {
        $answer = $this->Answers->newEntity($this->request->getData());
        $count = $this->Answers
                ->find()
                ->where(['question_id' => $answer->question_id])
                ->count();

        if ($count >= Answer::ANSWER_UPPER_LIMIT) {
            $this->Flash->error('回答の上限数に達しました');
            return $this->redirect(['action' => 'view', $answer->question_id]);
        }
        $answer->user_id = $this->Auth->user('id');

        if (!$this->Answers->save($answer)) {
            $this->Flash->error('回答の投稿に失敗しました');
            return $this->viewRendering($answer->question_id, $answer);
        }
        
        $this->Flash->success('回答を投稿しました');
        return $this->redirect(['action' => 'view', $answer->question_id]);
    }
}
