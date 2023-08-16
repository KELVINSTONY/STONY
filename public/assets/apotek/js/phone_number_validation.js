//Create Modal
$('#create').on('show.bs.modal', function () {
    var input = document.querySelector("#phone-number");
    var errorMsg = document.querySelector("#error-msg");
    var validMsg = document.querySelector("#valid-msg");
    validateMobile(input, errorMsg, validMsg);
});

//edit Modal
$('#edit').on('show.bs.modal', function () {
    var input = document.querySelector("#phone-number-1");
    var errorMsg = document.querySelector("#error-msg-1");
    var validMsg = document.querySelector("#valid-msg-1");
    validateMobile(input, errorMsg, validMsg);
});

//intenational Phone Number Validation
function validateMobile(input, errorMsg, validMsg, action) {
// here, the index maps to the error code returned from getValidationError
    var errorMap = ["Invalid Phone Number", "Invalid Country Code", "Too Short", "Too Long", "Invalid Phone Number"];
// initialise plugin
    input.addEventListener('keyup', reset);
// Check if its edit or create ie: on edit nationalMode is dissabled
    if (action) {
        var iti = window.intlTelInput(input, {
            customPlaceholder: function (selectedCountryPlaceholder, selectedCountryData) {
                return "e.g. " + selectedCountryPlaceholder;
            },
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "../../assets/plugins/intl-tel-input/js/utils.js?1562189064761",
            onlyCountries: ["tz", "ug", "ke", "rw", "bi", "sd"],
            nationalMode: false,
        });
    } else {
        var iti = window.intlTelInput(input, {
            customPlaceholder: function (selectedCountryPlaceholder, selectedCountryData) {
                return "e.g. " + selectedCountryPlaceholder;
            },
            initialCountry: "auto",
            geoIpLookup: function (callback) {
                $.get('https://ipinfo.io', function () {
                }, "jsonp").always(function (resp) {
                    var countryCode = (resp && resp.country) ? resp.country : "";
                    callback(countryCode);
                });
            },
            utilsScript: "../../assets/plugins/intl-tel-input/js/utils.js?1562189064761",
            onlyCountries: ["tz", "ug", "ke", "rw", "bi", "sd"],
        });
    }
    var reset = function () {
        input.classList.remove("error");
        errorMsg.innerHTML = "";
        errorMsg.classList.add("hide");
        validMsg.classList.add("hide");
    };

// on blur: validate
    input.addEventListener('blur', function () {
        reset();
        if (input.value.trim()) {
            if (iti.isValidNumber()) {
                $('#save_btn').prop('disabled', false);
                $('#edit_btn').prop('disabled', false);
                validMsg.classList.remove("hide");
                document.getElementById('phone-number').value = iti.getNumber();
                if (action) {//On edit there is action variable
                    document.getElementById('phone_edit').value = iti.getNumber();
                }
            } else {
                input.classList.add("error");
                $('#save_btn').prop('disabled', true);
                $('#edit_btn').prop('disabled', true);
                var errorCode = iti.getValidationError();
                notify(errorMap[errorCode], 'top', 'right', 'warning');
                errorMsg.innerHTML = errorMap[errorCode];
                errorMsg.classList.remove("hide");
            }
        }
    });

// on keyup / change flag: reset
    input.addEventListener('change', reset);
}
