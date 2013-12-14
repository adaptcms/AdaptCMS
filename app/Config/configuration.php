<?php
$system_config = '{"helpers":[],"components":[]}';

$configuration = array();
$configuration['system'] = json_decode($system_config, true);

Configure::write('internal', $configuration );

$global = '[{"tag":"{{ sitename }}","value":"AdaptCMS 3.0.2"},{"tag":"{{ description2 }}","value":"Howdy doodey partner!"}]';

Configure::write('global_vars', json_decode($global, true));
Configure::write('standard_global_vars', json_decode($global, true));