<div class="users form">
<?= $this->Flash->render('auth') ?>
    <?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Enter a new password to reset your account') ?></legend>
        <?= $this->Form->input('password') ?>
    </fieldset>
    <?= $this->Form->button(__('Signin')) ?>
    <?= $this->Form->end() ?>
</div>
