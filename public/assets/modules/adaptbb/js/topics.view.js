Vue.config.delimiters = ['{@', '@}'];

new Vue({
    el: '#topic',
    data: {
	message: '',
	ajaxUrl: $('.ui.button.comment').attr('data-href'),
	replies: []
    },
    methods: {
	triggerReply: function() {
	    $('#reply-box').toggleClass('hidden');

	    document.getElementById('reply-box').scrollIntoView();
	},
	submitReply: function() {
	    var data = {
		message: this.message,
		_token: $('meta[name="csrf-token"]').attr('content')
	    };

	    var _this = this;
	    $.post(this.ajaxUrl, data, function(response) {
		if (response.status) {
		    _this.message = '';

		    _this.replies.push(response.reply);

		    $('#reply-box').toggleClass('hidden');

		    toastr.success('Your reply has been made');
		} else {
		    toastr.error('Could not create your reply. Please try again.');
		}
	    }, 'json');
	}
    }
});
