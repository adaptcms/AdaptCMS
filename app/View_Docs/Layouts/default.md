There are certain things that you must have in your layout, we'll cover those first

`<?php echo $this->Html->script('jquery.min.js') ?>`

`<?php echo $this->Html->script('jquery.validate.min.js') ?>`

`<?php echo $this->Html->script('bootstrap.min.js') ?>`

`<?php echo $this->Html->script('global.js') ?>`

`<?php echo $this->AutoLoadJS->getJs() ?>`

`<?php echo $this->AutoLoadJS->getCss() ?>`


The above items load jquery, bootstrap, our global JS file and then autoloader of CSS/JS. All of the above is mandatory.

Another must have, this loads in the content - `<?php echo $this->fetch('content') ?>`. As well, you must have an ending `</body>` tag, this is used to write in a path to your site for
ajax in our js files.

What you really should have
---------------------------

These aren't must haves, but we highly recommend you do to retain certain non-essential functionality, usually asethetic.

This tag takes titles set in the templates and puts them in your title tag, great for SEO. `<?php echo $title_for_layout ?>`

    <?php
      echo $this->fetch('meta');
      echo $this->fetch('css');
      echo $this->fetch('script');
    ?>

We don't properly use the above block yet, but we will be implementing useage so keep this in for now.

* The default bootstrap theme `<?php echo $this->Html->css("bootstrap-default.min") ?>`
* Used for responsive designs (tablet, desktop, mobile) `<?php echo $this->Html->css("bootstrap-responsive.min") ?>`
* We mainly use the icons from this - `<?php echo $this->Html->css("font-awesome.min.css") ?>`

`<!--nocache-->
    <?php echo $this->Session->flash() ?>
<!--/nocache-->`

The last code bit displays flash messages, this should also stay.