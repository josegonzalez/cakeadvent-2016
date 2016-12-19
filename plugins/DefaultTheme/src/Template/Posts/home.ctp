<?php
$this->Paginator->templates([
    'nextDisabled' => implode(' ', [
        '<span class="fa-stack fa-lg">',
            '<i class="fa fa-square fa-stack-2x"></i>',
            '<i class="fa fa-angle-double-right fa-stack-1x fa-inverse"></i>',
        '</span>',
    ]),
    'nextActive' => implode(' ', [
        '<a rel="prev" href="{{url}}">',
            '<span class="fa-stack fa-lg">',
                '<i class="fa fa-square fa-stack-2x"></i>',
                '<i class="fa fa-angle-double-right fa-stack-1x fa-inverse"></i>',
            '</span>',
        '</a>',
    ]),
    'prevDisabled' => implode(' ', [
        '<span class="fa-stack fa-lg">',
            '<i class="fa fa-square fa-stack-2x"></i>',
            '<i class="fa fa-angle-double-left fa-stack-1x fa-inverse"></i>',
        '</span>',
    ]),
    'prevActive' => implode(' ', [
        '<a rel="prev" href="{{url}}">',
            '<span class="fa-stack fa-lg">',
                '<i class="fa fa-square fa-stack-2x"></i>',
                '<i class="fa fa-angle-double-left fa-stack-1x fa-inverse"></i>',
            '</span>',
        '</a>',
    ]),
]);

?>
<div class="wrapper">
    <ul class="post-list">
        <?php foreach ($posts as $post) : ?>
            <li>
                <?php
                    $postType = $post->getPostType();
                    echo $this->element($postType->indexTemplate(), ['post' => $postType]);
                ?>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
    <nav class="pagination" role="navigation">
        <p>
            <?= $this->Paginator->prev(); ?>
            <span class="page-number"><?= $this->Paginator->counter('Page {{page}} of {{pages}}'); ?></span>

            <?= $this->Paginator->next(); ?>
        </p>
    </nav>
</div>
