<?php

class DisqusHelper extends AppHelper
{
    public $name = 'Disqus';

    public function getComments($title = null)
    {
        $short_name = Configure::read('Disqus.disqus_name');
        $url = Router::url(null, true);

        return '<div id="disqus_thread"></div>
        <script type="text/javascript">
            var disqus_shortname = "' . $short_name . '";
            var disqus_url = "' . $url . '";
            var disqus_title = "' . $title . '";
            console.log(disqus_title);

            (function() {
                var dsq = document.createElement("script"); dsq.type = "text/javascript"; dsq.async = true;
                dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
                (document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>';
    }
}