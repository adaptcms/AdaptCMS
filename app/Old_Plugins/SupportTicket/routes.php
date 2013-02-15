<?php

Router::connect('/support', array('plugin' => 'SupportTicket', 'controller' => 'tickets', 'action' => 'index'));