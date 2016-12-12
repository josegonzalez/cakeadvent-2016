<?php
foreach ($indexActions as $config) {
    echo $this->element('CrudView.action-button', ['config' => $config]);
}
