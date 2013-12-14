<?php

Router::connect('/polls', array('plugin' => 'polls', 'controller' => 'polls'));
Router::connect('/polls/list', array('plugin' => 'polls', 'controller' => 'polls', 'action' => 'all'));