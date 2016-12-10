<?php
namespace App\Model\Table\Traits;

trait BlogFinderTrait
{
    /**
     * Find posts with related data
     *
     * @param \Cake\ORM\Query $query The query to find with
     * @param array $options The options to find with
     * @return \Cake\ORM\Query The query builder
     */
    public function findBlog($query, $options)
    {
        return $this->find()->contain('PostAttributes');
    }
}
