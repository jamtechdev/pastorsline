<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ContactsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'className' => 'Companies',
            'foreignKey' => 'company_id',
            'joinType' => 'LEFT',
        ]);  
    }


    public function validationDefault(Validator $validator)
    {
        $validator
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name', 'Please fill this field');

        $validator
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name', 'Please fill this field');

        $validator
            ->requirePresence('phone_number', 'create')
            ->notEmptyString('phone_number', 'Please fill this field');  

        $validator
            ->requirePresence('address', 'create')
            ->notEmptyString('address', 'Please fill this field');

        $validator
            ->requirePresence('notes', 'create')
            ->notEmptyString('notes', 'Please fill this field');  

        $validator
            ->requirePresence('add_notes', 'create')
            ->notEmptyString('add_notes', 'Please fill this field');  

        $validator
            ->requirePresence('internal_notes', 'create')
            ->notEmptyString('internal_notes', 'Please fill this field');

        $validator
            ->requirePresence('comments', 'create')
            ->notEmptyString('comments', 'Please fill this field');                        
            
        return $validator;
    }
}