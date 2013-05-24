<?php

$params = '{"disqus_name":"cp88"}';

$config = json_decode($params, true);
Configure::write('Disqus', $config );
?>