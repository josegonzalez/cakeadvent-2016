<?php
namespace App\Model\Entity\Traits;

use App\Model\Entity\Post;
use App\Model\Table\PostsTable;
use Cake\Core\App;

trait PostTypeTrait
{
    public function getPostType()
    {
        $postTypeClassName = $this->_postTypeAliasToClass($this->type);
        $className = App::className($postTypeClassName, 'PostType');
        return new $className($this);
    }

    /**
     * Returns a class name for a given post type alias
     *
     * @param string $typeAlias the alias of a post type class
     * @return string
     */
    protected function _postTypeAliasToClass($typeAlias)
    {
        $className = null;
        $postTypes = PostsTable::postTypes();
        foreach ($postTypes as $class => $alias) {
            if ($alias === $typeAlias) {
                $className = $class;
            }
        }
        return $className;
    }
}
