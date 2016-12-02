<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * User shell command.
 */
class UserShell extends Shell
{

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main()
    {
        $data = [];
        $fields = [
            $this->params['username-field'],
            $this->params['password-field']
        ];
        foreach ($fields as $field) {
            $value = null;
            $fieldName = Inflector::humanize($field);
            while (empty($value)) {
                $value = $this->in(sprintf('%s?', $fieldName));
            }
            $data[$field] = $value;
        }

        $this->out('');
        $continue = $this->in('Continue?', ['y', 'n'], 'n');
        if ($continue !== 'y') {
            return $this->error('User not saved.');
        }
        $this->out('');
        $this->hr();

        $table = TableRegistry::get($this->params['table']);
        $entity = $table->newEntity($data, ['validate' => false]);
        if (!$table->save($entity)) {
            return $this->error(sprintf('User could not be inserted: %s', print_r($entity->errors(), true)));
        }
        $this->out(sprintf('User inserted! ID: %d, Data: %s', $entity->id, print_r($entity->toArray(), true)));
    }


    /**
     * UserShell
     *
     * @return ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->description('The User shell can create a user on the fly for local development.');

        $parser->addOption('table', [
            'short' => 't',
            'help' => 'Name of Table class (with plugin prefix) to use to create a user',
            'default' => 'Users',
        ]);
        $parser->addOption('username-field', [
            'short' => 'u',
            'help' => 'Name of username field',
            'default' => 'username',
        ]);
        $parser->addOption('password-field', [
            'short' => 'p',
            'help' => 'Name of password field',
            'default' => 'password',
        ]);
        return $parser;
    }
}
