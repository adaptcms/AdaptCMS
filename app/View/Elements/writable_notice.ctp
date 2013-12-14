<div class="alert alert-warning">
    Notice
</div>
<p>
    This <?php echo $type ?> file is not writable. Please chmod 777 the following file<?php if (!empty($sensitive)): ?>, if it contains sensitive information (such as an API user/pass), chmod 644 the file after submitting this form<?php endif ?>:<br />
<p>
<p>
    <strong><?php echo $file ?></strong>
</p>