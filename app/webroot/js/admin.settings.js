$(document).ready(function(){
    $('.admin-validate .delete').on('change', function() {
        if ($(this).attr('checked'))
        {
            if (!confirm('Are you sure you wish to delete this setting? This is permanent.'))
            {
                $(this).attr('checked', false);
            }
        }
    });
});