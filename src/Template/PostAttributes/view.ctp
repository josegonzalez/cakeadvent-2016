<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Post Attribute'), ['action' => 'edit', $postAttribute->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Post Attribute'), ['action' => 'delete', $postAttribute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $postAttribute->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Post Attributes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post Attribute'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Posts'), ['controller' => 'Posts', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Post'), ['controller' => 'Posts', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="postAttributes view large-9 medium-8 columns content">
    <h3><?= h($postAttribute->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Post') ?></th>
            <td><?= $postAttribute->has('post') ? $this->Html->link($postAttribute->post->id, ['controller' => 'Posts', 'action' => 'view', $postAttribute->post->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($postAttribute->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($postAttribute->id) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Value') ?></h4>
        <?= $this->Text->autoParagraph(h($postAttribute->value)); ?>
    </div>
</div>
