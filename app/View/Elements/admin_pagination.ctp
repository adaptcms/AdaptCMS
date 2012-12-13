<?php
    $numbers = $this->Paginator->numbers(array('separator' => false, 'tag' => 'li', 'currentClass' => 'active paginator', 'first' => '1'));
?>

<?php if (!empty($numbers)): ?>
    <div class="pagination">
        <ul>
            <?= $this->Paginator->prev('«', array('tag' => 'li'), '<li><a>«</a></li>', array('escape' => false)) ?>
            <?= $numbers ?>
            <?= $this->Paginator->next('»', array('tag' => 'li'), '<li><a>«</a></li>', array('escape' => false)) ?>
        </ul>
    </div>

    <?=  $this->Paginator->counter('Showing records <strong>{:start}-{:end}</strong> out of <strong>{:count}</strong> total', array('escape' => false)) ?>
<?php endif ?>