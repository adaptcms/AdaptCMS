<?php
    $numbers = $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentClass' => 'active paginator', 'first' => '1'));
?>

<?php if (!empty($numbers)): ?>
    <div class="pagination">
        <ul>
            <?= $this->Paginator->prev('«', array('tag' => 'li'), '<a>«</a>', array('escape' => false, 'class' => 'disabled')) ?>
            <?= $numbers ?>
            <?= $this->Paginator->next('»', array('tag' => 'li'), '<a>«</a>', array('escape' => false, 'class' => 'next disabled')) ?>
        </ul>
    </div>

    <?=  $this->Paginator->counter('Showing records <strong>{:start}-{:end}</strong> out of <strong>{:count}</strong> total', array('escape' => false)) ?>
<?php endif ?>