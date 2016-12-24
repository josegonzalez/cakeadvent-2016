<?php
namespace PhotoPostType\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @method \PhotoPostType\Model\Entity\Order get($primaryKey, $options = [])
 * @method \PhotoPostType\Model\Entity\Order newEntity($data = null, array $options = [])
 * @method \PhotoPostType\Model\Entity\Order[] newEntities(array $data, array $options = [])
 * @method \PhotoPostType\Model\Entity\Order|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \PhotoPostType\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \PhotoPostType\Model\Entity\Order[] patchEntities($entities, array $data, array $options = [])
 * @method \PhotoPostType\Model\Entity\Order findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrdersTable extends Table
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

        $this->table('orders');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('OrderNotificationBehavior');
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('chargeid', 'create')
            ->notEmpty('chargeid');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('shipping_name', 'create')
            ->notEmpty('shipping_name');

        $validator
            ->requirePresence('shipping_address_line_1', 'create')
            ->notEmpty('shipping_address_line_1');

        $validator
            ->requirePresence('shipping_address_zip', 'create')
            ->notEmpty('shipping_address_zip');

        $validator
            ->requirePresence('shipping_address_state', 'create')
            ->notEmpty('shipping_address_state');

        $validator
            ->requirePresence('shipping_address_city', 'create')
            ->notEmpty('shipping_address_city');

        $validator
            ->requirePresence('shipping_address_country', 'create')
            ->notEmpty('shipping_address_country');

        $validator
            ->boolean('shipped')
            ->requirePresence('shipped', 'create')
            ->notEmpty('shipped');

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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }
}
