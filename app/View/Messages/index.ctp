<?php $this->Html->addCrumb('Profile', array(
    'action' => 'profile',
    'controller' => 'users',
    $this->Session->read('Auth.User.username')
)) ?>
<?php $this->Html->addCrumb('Messages', null) ?>

<div class="left">
    <h1>Messages - <?= ucfirst($box) ?></h1>
</div>
<div class="btn-group pull-right" style="margin-bottom:10px">
  <a class="btn dropdown-toggle" data-toggle="dropdown">
    View <i class="icon-picture"></i>
    <span class="caret"></span>
  </a>
  <ul class="dropdown-menu" style="min-width: 0px">
    <li<?= ($box == 'inbox' ? ' class="active"' : '') ?>>
        <?= $this->Html->link('Inbox', array('inbox')) ?>
    </li>
    <li<?= ($box == 'outbox' ? ' class="active"' : '') ?>>
        <?= $this->Html->link('Outbox', array('outbox')) ?>
    </li>
    <li<?= ($box == 'sentbox' ? ' class="active"' : '') ?>>
        <?= $this->Html->link('Sentbox', array('sentbox')) ?>
    </li>
    <li<?= ($box == 'archive' ? ' class="active"' : '') ?>>
        <?= $this->Html->link('Archive', array('archive')) ?>
    </li>
  </ul>
</div>
<div class="clear"></div>

<?= $this->Html->link('Send Message', array('action' => 'send'), array(
    'class' => 'btn btn-primary pull-right', 
    'style' => 'margin-bottom:10px;margin-right: 10px'
)) ?>

<?php if (!empty($messages)): ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('is_read', 'Read') ?></th>
                <?php if ($box == 'archive'): ?>
                    <th>Box</th>
                <?php endif ?>
                <th><?= $this->Paginator->sort('title', 'Subject') ?></th>
                <th><?= $this->Paginator->sort('Sender.username', 'From') ?>
                <th><?= $this->Paginator->sort('Receiver.username', 'To') ?>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('last_reply_time', 'Last Reply') ?></th>
                <?php if ($box != 'outbox'): ?>
                    <th>Options</th>
                <?php endif ?>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($messages as $message): ?>
            <tr>
                <td>
                    <?php if ($message['Message']['is_read'] == 1): ?>
                        <i class="icon icon-ok"></i>
                    <?php else: ?>
                        <i class="icon icon-remove"></i>
                    <?php endif ?>
                </td>
                <?php if ($box == 'archive'): ?>
                    <td>
                        <?php if ($message['Sender']['username'] == $username && $message['Message']['is_read'] == 0): ?>
                            <?= $this->Html->link('Outbox', array(
                                'action' => 'index',
                                'outbox'
                            )) ?>
                        <?php elseif($message['Sender']['username'] == $username): ?>
                            <?= $this->Html->link('Sentbox', array(
                                'action' => 'index',
                                'sentbox'
                            )) ?>
                        <?php else: ?>
                            <?= $this->Html->link('Inbox', array(
                                'action' => 'index',
                                'inbox'
                            )) ?>
                        <?php endif ?>
                    </td>
                <?php endif ?>
                <td>
                    <?php if ($message['Message']['parent_id'] == 0): ?>
                        <?= $this->Html->link($message['Message']['title'], array(
                            'action' => 'view',
                            $message['Message']['id'],
                            Inflector::slug($message['Message']['title'])
                        )) ?>
                    <?php else: ?>
                        <?= $this->Html->link($message['Message']['title'], array(
                            'action' => 'view',
                            $message['Message']['parent_id'],
                            Inflector::slug($message['Message']['title']),
                            '#' => 'message-' . $message['Message']['id']
                        )) ?>
                    <?php endif ?>
                </td>
                <td>
                    <?= $this->Html->link($message['Sender']['username'], array(
                        'controller' => 'users',
                        'action' => 'profile',
                        $message['Sender']['username']
                    )) ?>
                </td>
                <td>
                    <?= $this->Html->link($message['Receiver']['username'], array(
                        'controller' => 'users',
                        'action' => 'profile',
                        $message['Receiver']['username']
                    )) ?>
                </td>
                <td>
                    <?= $this->Admin->time($message['Message']['created'], 'words') ?>
                </td>
                <td>
                    <?php if ($message['Message']['last_reply_time'] == '0000-00-00 00:00:00'): ?>
                        No Replies
                    <?php else: ?>
                        <?= $this->Admin->time($message['Message']['last_reply_time'], 'words') ?>
                    <?php endif ?>
                </td>
                <?php if ($box != 'outbox'): ?>
                    <td>
                        <?php if ($box != 'archive' && $message['Message']['is_read'] == 0 && $message['Message']['receiver_user_id'] == $this->Session->read('Auth.User.id')): ?>
                            <?= $this->Html->link(
                                '<i class="icon-check icon-white"></i> Mark Read', 
                                array('action' => 'move', 'mark_read', $message['Message']['id']),
                                array('class' => 'btn btn-primary', 'escape' => false))
                            ?>
                        <?php endif ?>
                        <?php if ($box != 'archive'): ?>
                            <?= $this->Html->link(
                                '<i class="icon-move icon-white"></i> Archive Message',
                                array('action' => 'move', 'archive', $message['Message']['id']),
                                array('class' => 'btn btn-info', 'escape' => false))
                            ?>
                        <?php elseif ($box == 'archive'): ?>
                            <?= $this->Html->link(
                                '<i class="icon-share-alt icon-white"></i> Move to Inbox', 
                                array('action' => 'move', 'inbox', $message['Message']['id']),
                                array('class' => 'btn btn-success', 'escape' => false))
                            ?>     
                        <?php endif ?>
                    </td>
                <?php endif ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?= $this->element('admin_pagination') ?>
<?php else: ?>
    <div class="well span12 no-marg-left">
        <p>
            No Messages in <?= ucfirst($box) ?>
        </p>
    </div>
<?php endif ?>