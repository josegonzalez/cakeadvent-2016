<div class="posts index large-12 medium-12 columns content">
    <?php $postType = $post->getPostType(); ?>
    <?= $this->element($postType->viewTemplate(), ['post' => $postType]); ?>
</div>
