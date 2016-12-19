<h2><?= $this->Html->link($post->get('title'), $post->get('url')) ?></h2>
<section class="post-meta">
    <div class="post-date"><?= $this->Time->nice($post->get('published_date')) ?></div>
</section>
<section class="post-excerpt" itemprop="description">
    <?= $post->get('body') ?>
</section>
