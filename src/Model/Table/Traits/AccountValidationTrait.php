<?php
namespace App\Model\Table\Traits;

use Cake\Validation\Validator;

trait AccountValidationTrait
{
    /**
     * Account validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAccount(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        $validator->remove('password');
        $validator->allowEmpty('confirm_password');
        $validator->add('confirm_password', 'no-misspelling', [
            'rule' => ['compareWith', 'password'],
            'message' => 'Passwords are not equal',
        ]);
        return $validator;
    }
}
