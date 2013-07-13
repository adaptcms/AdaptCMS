<?php
$numbers = $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentClass' => 'active paginator', 'first' => '1'));
?>
<?php if (!empty($numbers)): ?>
    <div class="pagination">
        <ul>
            <?php
            echo $this->Paginator->prev(__('«'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
            echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
            echo $this->Paginator->next(__('»'), array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
            ?>
        </ul>
    </div>

    <?=  $this->Paginator->counter('Showing records <strong>{:start}-{:end}</strong> out of <strong>{:count}</strong> total', array('escape' => false)) ?>
<?php endif ?>