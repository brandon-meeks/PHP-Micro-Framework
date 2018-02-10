$.validator.addMethod('validPassword',
    function(value, element, param) {
        if (value != '') {
            if (value.match(/.*[a-z]+.*/i) == null) {
                return false;
            }
            if (value.match(/.*\d+.*/) == null) {
                return false;
            }
        }
        return true;
    },
    'Must contain at least one letter and one number'
);

$(document).ready(function() {
    $('#signup').validate({
        rules: {
            name: "required",
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength : 6,
                validPassword: true
            },
            passwordConfirmation: {
                equalTo: '#password'
            }
        },
        success: "valid"
    });

    $('#loginForm').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        }
    });
    $('#password').togglePassword(true);
});