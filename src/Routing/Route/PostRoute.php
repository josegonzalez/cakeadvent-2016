<?php
namespace App\Routing\Route;

use Cake\ORM\TableRegistry;
use Cake\Routing\Route\Route;

class PostRoute extends Route
{
    public function parse($url, $method = '')
    {
        $params = parent::parse($url, $method);
        if (empty($params)) {
            return false;
        }

        $PostsTable = TableRegistry::get('Posts');
        $post = $PostsTable->find()->where(['url' => '/' . $params['url']])->first();
        if (empty($post)) {
            return false;
        }

        $params['pass'] = [$post->id];
        return $params;
    }
}
