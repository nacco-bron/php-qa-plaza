<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Answer;
use Cake\Event\Event;

/**
 * Answers Controller
 *
 * @property \App\Model\Table\AnswersTable $Answers
 *
 * @method \App\Model\Entity\Answer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AnswersController extends AppController
{

    /**
     * @inheritdoc
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->request->allowMethod(['post']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Answer id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(int $id)
    {
        $answer = $this->Answers->get($id);
        $questionId = $answer->question_id;
        if ($answer->user_id != $this->Auth->user('id')) {
            $this->Flash->error('他のユーザーの回答を削除することはできません');
            return $this->redirect(['controller' => 'Questions', 'action' => 'view', $questionId]);
        }

        if ($this->Answers->delete($answer)) {
            $this->Flash->success('回答を削除しました');
        } else {
            $this->Flash->error('回答の削除に失敗しました');
        }

        return $this->redirect(['controller' => 'Questions', 'action' => 'view', $questionId]);
    }
}
