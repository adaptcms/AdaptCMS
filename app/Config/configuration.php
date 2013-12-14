<?php
$system_config = '{"helpers":[],"components":[]}';

$configuration = array();
$configuration['system'] = json_decode($system_config, true);

Configure::write('internal', $configuration );