<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Logout Controller
 *
 *
 * @method \App\Model\Entity\Logout[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LogoutController extends AppController
{
    /**
     * @inheritdoc
     */
    public function initialize()
    {
        parent::initialize();
        $this->Auth->deny(['index']);
    }

    /**
     * ログアウト処理
     *
     * @return \Cake\Http\Response|null ログアウト後にログインTOPに遷移する
     */
    public function index()
    {
        $this->Flash->success('ログアウトしました');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * View method
     *
     * @param string|null $id Logout id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $logout = $this->Logout->get($id, [
            'contain' => []
        ]);

        $this->set('logout', $logout);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $logout = $this->Logout->newEntity();
        if ($this->request->is('post')) {
            $logout = $this->Logout->patchEntity($logout, $this->request->getData());
            if ($this->Logout->save($logout)) {
                $this->Flash->success(__('The logout has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The logout could not be saved. Please, try again.'));
        }
        $this->set(compact('logout'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Logout id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $logout = $this->Logout->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $logout = $this->Logout->patchEntity($logout, $this->request->getData());
            if ($this->Logout->save($logout)) {
                $this->Flash->success(__('The logout has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The logout could not be saved. Please, try again.'));
        }
        $this->set(compact('logout'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Logout id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $logout = $this->Logout->get($id);
        if ($this->Logout->delete($logout)) {
            $this->Flash->success(__('The logout has been deleted.'));
        } else {
            $this->Flash->error(__('The logout could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
