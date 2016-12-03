<div class="users form">
<?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your email to send a reset email') ?></legend>
        <?= $this->Form->input('email') ?>
    </fieldset>
    <?= $this->Form->button(__('Reset password')); ?>
    <?= $this->Form->end() ?>
</div>
