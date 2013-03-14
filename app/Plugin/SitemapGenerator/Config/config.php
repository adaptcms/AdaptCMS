<?php

$params = '{"number_of_days_between_generation":"14","yahoo_sitemap_key":""}';

$config = json_decode($params, true);
Configure::write('SitemapGenerator', $config );
?>