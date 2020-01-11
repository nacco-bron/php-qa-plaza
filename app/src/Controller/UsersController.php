<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * @inheritdoc
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add']);
    }

    /**
     * ユーザー登録画面/ユーザー登録処理
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success('ユーザーの登録が完了しました');

                return $this->redirect(['controller' => 'Login', 'action' => 'index']);
            }
            $this->Flash->error('ユーザーの登録に失敗しました');
        }
        $this->set(compact('user'));
    }

    /**
     * ユーザー編集画面/ユーザー情報更新処理
     *
     * @return \Cake\Http\Response|null ユーザー情報更新後に質問一覧画面へ遷移する
     */
    public function edit()
    {
        $user = $this->Users->get($this->Auth->user('id'));
        if ($this->request->is(['put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Auth->setUser($user->toArray());
                $this->Flash->success('ユーザー情報を更新しました');
            } else {
                $this->Flash->error('ユーザー情報の更新に失敗しました');
            }
        }
        $this->set(compact('user'));
    }

    /**
     * パスワード更新画面/パスワード更新処理
     *
     * @return \Cake\Http\Response|null パスワード更新後にユーザー編集画面へ遷移する
     */
    public function password()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is(['post'])) {
            $user = $this->Users->get($this->Auth->user('id'));

            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Auth->setUser($user->toArray());
                $this->Flash->success('パスワードを更新しました');
            } else {
                $this->Flash->error('パスワードの更新に失敗しました');
            }
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
