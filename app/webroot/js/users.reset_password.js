$(document).ready(function() {
    jQuery.validator.addMethod("notEqual", function(value, element, param) {
        return this.optional(element) || value != $(param).val();
    }, "Your new password must be different than your previous one");

    $("#UserPassword").rules("add", {
        required: true,
        notEqual: "#UserPasswordCurrent"
    });

    $("#UserPasswordCurrent").rules("add", {
        required: true,
        notEqual: "#UserPassword"
    });
});