<h2><?= $post->get('title') ?></h2>
<section class="post-meta">
    <div class="post-date"><?= $this->Time->nice($post->get('published_date')) ?></div>
</section>
<section class="post-excerpt" itemprop="description">
    <?= $this->Html->image('../' . $post->get('photo_path')) ?>
</section>

<?= $this->element('stripe', ['post' => $post]); ?>
