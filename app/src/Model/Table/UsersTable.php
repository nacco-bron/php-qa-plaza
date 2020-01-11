<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Model
 *
 * @property \App\Model\Table\AnswersTable|\Cake\ORM\Association\HasMany $Answers
 * @property \App\Model\Table\QuestionsTable|\Cake\ORM\Association\HasMany $Questions
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        // $this->hasMany('Answers', [
        //     'foreignKey' => 'user_id'
        // ]);
        // $this->hasMany('Questions', [
        //     'foreignKey' => 'user_id'
        // ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id', 'IDが不正です')
            ->allowEmpty('id', 'create', 'IDが不正です');

        $validator
            ->scalar('password', 'ユーザ名が不正です')
            ->notEmpty('username', 'ユーザー名は必ず入力してください')
            ->maxLength('username', 10, 'ユーザー名は10文字以内で入力してください')
            ->requirePresence('username', 'create')
            ->add('username', 'alphaNumeric', [
                'rule' => function($value){
                    $pattern = '/\A[a-zA-Z0-9]+\z/';
                    return (bool)preg_match($pattern, $value);
                },
                'message' => 'ユーザー名は半角英数字のみを入力してください'
            ])
            ->add('username', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'そのユーザー名は既に使用されています'
            ]);

        $validator
            ->add('password', 'securePassword', [
                'rule' => function($value){
                    $pattern = '/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]+\z/i';
                    return (bool)preg_match($pattern, $value);
                },
                'message' => 'パスワードは半角英数字混合で入力してください'
            ])            
            ->scalar('password', 'パスワードが不正です')
            ->requirePresence('password', 'create', 'パスワードが不正です')
            ->notEmpty('password', 'パスワードは必ず入力してください')
            ->lengthBetween('password', [8, 16], 'パスワードは8文字以上16文字以内で入力してください')
            ->add('password', [
                'compare' => [
                    'rule' => ['compareWith', 'password_confirm'],
                    'message' => '確認用パスワードと一致しません'
                ]
            ]);

        $validator
            ->add('password_current','check',[
                'rule' => function($value, $context){
                    $user = $this->get($context['data']['id']);
                    if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                        return true;
                    }
                    return false;
                },
                'message' => '現在のパスワードが正しくありません'
            ]);

        $validator
            ->scalar('nickname', 'ニックネームが不正です')
            ->maxLength('nickname', 20, 'ニックネームは20文字以内で入力してください')
            ->requirePresence('nickname', 'create')
            ->notEmpty('nickname');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmpty('created_at');

        $validator
            ->dateTime('updated_at')
            ->allowEmpty('updated_at');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }
}
