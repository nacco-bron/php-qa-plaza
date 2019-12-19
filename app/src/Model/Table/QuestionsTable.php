<?php
namespace App\Model\Table;

// use Cake\ORM\Query;
// use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
// use Cake\Validation\Validator;

/**
 * Questions Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\AnswersTable|\Cake\ORM\Association\HasMany $Answers
 *
 * @method \App\Model\Entity\Question get($primaryKey, $options = [])
 * @method \App\Model\Entity\Question newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Question[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Question|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Question|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Question patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Question[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Question findOrCreate($search, callable $callback = null, $options = [])
 */
class QuestionsTable extends Table
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

        $this->setTable('questions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        // $this->belongsTo('Users', [
        //     'foreignKey' => 'user_id',
        //     'joinType' => 'INNER'
        // ]);
        $this->hasMany('Answers', [
            'foreignKey' => 'question_id'
        ]);
    }

    // /**
    //  * Default validation rules.
    //  *
    //  * @param \Cake\Validation\Validator $validator Validator instance.
    //  * @return \Cake\Validation\Validator
    //  */
    // public function validationDefault(Validator $validator)
    // {
    //     $validator
    //         ->nonNegativeInteger('id')
    //         ->allowEmpty('id', 'create');

    //     $validator
    //         ->scalar('body')
    //         ->maxLength('body', 255)
    //         ->requirePresence('body', 'create')
    //         ->notEmpty('body');

    //     $validator
    //         ->dateTime('created_at')
    //         ->requirePresence('created_at', 'create')
    //         ->notEmpty('created_at');

    //     $validator
    //         ->dateTime('updated_at')
    //         ->allowEmpty('updated_at');

    //     return $validator;
    // }

    // /**
    //  * Returns a rules checker object that will be used for validating
    //  * application integrity.
    //  *
    //  * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
    //  * @return \Cake\ORM\RulesChecker
    //  */
    // public function buildRules(RulesChecker $rules)
    // {
    //     $rules->add($rules->existsIn(['user_id'], 'Users'));

    //     return $rules;
    // }
}
