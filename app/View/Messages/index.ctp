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

<?= $this->Html->link('Send Message', array('action' => 'send'), array('class' => 'btn btn-primary pull-right', 'style' => 'margin-bottom:10px;margin-right: 10px')) ?>

<?php if (!empty($this->request->data)): ?>
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
            <?php foreach ($this->request->data as $data): ?>
            <tr>
                <td>
                    <?php if ($data['Message']['is_read'] == 1): ?>
                        <i class="icon icon-ok"></i>
                    <?php else: ?>
                        <i class="icon icon-remove"></i>
                    <?php endif ?>
                </td>
                <?php if ($box == 'archive'): ?>
                    <td>
                        <?php if ($data['Sender']['username'] == $username && $data['Message']['is_read'] == 0): ?>
                            <?= $this->Html->link('Outbox', array(
                                'action' => 'index',
                                'outbox'
                            )) ?>
                        <?php elseif($data['Sender']['username'] == $username): ?>
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
                    <?php if ($data['Message']['parent_id'] == 0): ?>
                        <?= $this->Html->link($data['Message']['title'], array(
                            'action' => 'view',
                            $data['Message']['id'],
                            Inflector::slug($data['Message']['title'])
                        )) ?>
                    <?php else: ?>
                        <?= $this->Html->link($data['Message']['title'], array(
                            'action' => 'view',
                            $data['Message']['parent_id'],
                            Inflector::slug($data['Message']['title']),
                            '#' => 'message-' . $data['Message']['id']
                        )) ?>
                    <?php endif ?>
                </td>
                <td>
                    <?= $this->Html->link($data['Sender']['username'], array(
                        'controller' => 'users',
                        'action' => 'profile',
                        $data['Sender']['username']
                    )) ?>
                </td>
                <td>
                    <?= $this->Html->link($data['Receiver']['username'], array(
                        'controller' => 'users',
                        'action' => 'profile',
                        $data['Receiver']['username']
                    )) ?>
                </td>
                <td>
                    <?= $this->Admin->time($data['Message']['created'], 'words') ?>
                </td>
                <td>
                    <?php if ($data['Message']['last_reply_time'] == '0000-00-00 00:00:00'): ?>
                        No Replies
                    <?php else: ?>
                        <?= $this->Admin->time($data['Message']['last_reply_time'], 'words') ?>
                    <?php endif ?>
                </td>
                <?php if ($box != 'outbox'): ?>
                    <td>
                        <?php if ($box != 'archive' && $data['Message']['is_read'] == 0 && $data['Message']['receiver_user_id'] == $this->Session->read('Auth.User.id')): ?>
                            <?= $this->Html->link(
                                '<i class="icon-check icon-white"></i> Mark Read', 
                                array('action' => 'move', 'mark_read', $data['Message']['id']),
                                array('class' => 'btn btn-primary', 'escape' => false))
                            ?>
                        <?php endif ?>
                        <?php if ($box != 'archive'): ?>
                            <?= $this->Html->link(
                                '<i class="icon-move icon-white"></i> Archive Message',
                                array('action' => 'move', 'archive', $data['Message']['id']),
                                array('class' => 'btn btn-info', 'escape' => false))
                            ?>
                        <?php elseif ($box == 'archive'): ?>
                            <?= $this->Html->link(
                                '<i class="icon-share-alt icon-white"></i> Move to Inbox', 
                                array('action' => 'move', 'inbox', $data['Message']['id']),
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