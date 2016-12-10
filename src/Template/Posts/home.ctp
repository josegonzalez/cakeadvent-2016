<div class="posts index large-12 medium-12 columns content">
    <h3><?= __('Posts') ?></h3>
    <?php
        foreach ($posts as $post) {
            $postType = $post->getPostType();
            echo $this->element($postType->indexTemplate(), ['post' => $postType]);
        }
    ?>
</div>
