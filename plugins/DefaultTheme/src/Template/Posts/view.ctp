<div class="wrapper">
    <ul class="post-list">
        <li>
            <?php $postType = $post->getPostType(); ?>
            <?= $this->element($postType->viewTemplate(), ['post' => $postType]); ?>
        </li>
    </ul>
</div>
