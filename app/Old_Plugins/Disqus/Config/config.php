<?php

$params = '{"disqus_name":""}';

$config = json_decode($params, true);
Configure::write('Disqus', $config );
?>