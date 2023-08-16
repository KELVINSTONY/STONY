var lab_test_cart = [];
var table_block = false;
var test_to_add = null;

var lab_tests_table = $('#lab_tests_table').DataTable({
    order: [
        [1, "asc"]
    ],
    // dom: 't',
    pageLength: 100,
    columns: [
        {
            title: "ID",
            data: "test_id"
        },
        {
            title: "status",
            data: "lab_test_order_id"
        },
        {
            title: "Test Name",
            data: "test_name"
        },

        {
            title: "Parameter",
            data: "parameters",
            render: function (data) {
                return `${data?.length ?? 'No'} Parameters`
            }
        },
        {
            title: "Payment",
            data: "price_category.name"
        },
        {
            title: "Action",
            data: "action",
            defaultContent: "<input type='button' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/>"
        }

    ]

});

var test_param_table = $('#test_param_table').DataTable({
    ordering: false,
    dom: 't',
    pageLength: 100,
    columns: [
        {
            title: "TestID",
            data: 'lab_test_id'
        },
        {
            title: "ParamID",
            data: 'id'
        },
        {
            title: "Name",
            data: "name"
        },
        {
            title: "Unit",
            data: "unit"
        },
        {
            title: "Action",
            defaultContent: "<input type='button' value='Remove' id='remove_btn' class='btn btn-danger btn-rounded btn-sm'/>"
        }

    ]

});

var parameters_to_add_table = $('#parameters_to_add_table').DataTable({
    ordering: false,
    dom: 't',
    pageLength: 100,
    columns: [
        {
            title: "Name",
            data: "name",
            render: function (name, type, row) {
                if (!row.nhif_code) {
                    return name;
                }
                if (row.valid_approval_ref === false) {
                    return name;
                }

                return `
                <div class="d-flex align-items-center">
                ${name} ${row.valid ? '<span class="text-success ml-2"><i class="fas fa-check"></i></span>' : '<span class="text-danger ml-2"><i class="fas fa-times"></i></span>'}
                </div>
                `
            }
        },
        {
            title: "Appr Ref #",
            data: "require_approval",
            render: function (require_approval, type, row) {
                if (!require_approval) {
                    return null;
                }
                let input = `<input value="${row.approval_ref_no ? row.approval_ref_no : ''}" class="parameter_approval form-control form-control-sm" type="text"/>`
                if (!row.approval_ref_no) {
                    return input;
                }
                return `
                <div class="d-flex align-items-center">
                ${input} ${row.valid_approval_ref ? '<span class="ml-2 text-success"><i class="fas fa-check"></i></span' : '<span class="text-danger ml-2"><i class="fas fa-times"></i></span'}
                </div>
                `
            }
        },
        {
            title: "Restricted",
            data: "require_approval",
            render: function (require_approval) {
                return require_approval ? "YES" : "NO";
            }
        },
        {
            title: "Action",
            defaultContent: "<input type='button' value='Remove' id='remove_parameter_to_add' class='btn btn-danger btn-rounded btn-sm'/>"

        }

    ]

});

function getPriceCategory(id) {

    // price category options
    let price_options = Array.from(document.getElementById('Payments')?.options);

    /* Options values are stringfied object and id is (id of category)
        to get price category value  we have to get option value with id equals to id
     */
    let select_option = price_options.reduce(function (selected, current) {
        let current_value = JSON.parse(current.value) ?? null;
        return current_value?.id == id ? current_value : selected
    }, null);

    return select_option
}

function addTestToCart() {

    if (!test_to_add) {
        return;
    }

    lab_test_cart.push({
        lab_test_order_id: null,
        test_id: test_to_add.id,
        test_name: test_to_add.name,
        price_category: test_to_add.price_category,
        parameters: test_to_add.parameters,
        result_status: null,
        approval_ref_no: test_to_add.approval_ref_no,
        require_approval: test_to_add.require_approval,
        action: test_to_add.action
    });

    drawLabTestTable();

    submit_values();

    test_to_add = null;
}

function addOrderToCart(order) {

    lab_test_cart.push({
        lab_test_order_id: order.id,
        test_id: order.test.id,
        test_name: order.test.name,
        price_category: order.price_category,
        parameters: order.parameters,
        result_status: order.result_status,
        approval_ref_no: order.approval_ref_no,
        require_approval: order.require_approval,
        action: order.action
    });

    drawLabTestTable();

    submit_values();
}

$('#test').on('change', function () {

    if (table_block) {
        notify('You Cannot Add Tests to the Past Visit', 'top', 'right', 'danger');
        document.getElementById("test").value = '';
        return
    }

    var test = JSON.parse(document.getElementById("test").value);

    document.getElementById("test").value = '';

    let is_duplicate = lab_test_cart.some(function (cart_test) {
        return cart_test.test_id == test.id
    });

    if (is_duplicate) {

        let duplicate_with_no_result = lab_test_cart.some(function (cart_test) {
            return cart_test.test_id == test.id && !cart_test.result_status
        });

        if (duplicate_with_no_result) {
            notify('Lab test with no results exists', 'top', 'right', 'warning');
            return
        }

        let dialog = window.confirm(`${test.name} exists, do want to order it again?`)

        if (!dialog) {
            return
        }

    }

    test.action = "<input type='button' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/>";

    test.price_category = getPriceCategory(visitPriceCategory);
    test.require_approval = false;
    test.approval_ref_no = null;
    test.valid_approval_ref = null;
    test.valid = true;
    test_to_add = test;
    checkTestRestriction();
});


$('#submit_btn').click(function () {
    $('#submit_btn').attr('disabled', true);
    $('#issue').submit();
    return true;
});


function drawLabTestTable() {
    lab_tests_table.columns([0, 1]).visible(false);
    if (table_block) {
        if (typeof ViewResult !== 'undefined') {
            lab_tests_table.column(4).visible(ViewResult);
        } else {
            lab_tests_table.column(4).visible(false);
        }
        $('#submit_btn').prop('disabled', true);
    }
    lab_tests_table.clear();
    lab_tests_table.rows.add(lab_test_cart);
    lab_tests_table.draw();
    getTotalPrice(lab_test_cart);
}

function getTotalPrice(cart) {
    let tests = cart.map(item=>{
        return {
            id:item.test_id,
            price_category: item.price_category
        };
    });

    $.ajax({
        url: urls.routes.getTotalPrice,
        data: {tests},
        type: 'get',
        dataType: 'json',
        cache: false,
        success: function (data) {
            var pricess = new Intl.NumberFormat({ style: 'currency' }).format(data.price);
            $("#total_prices").html(pricess);
        }
    });
}
function drawTestParamTable(test_param) {
    test_param_table.columns([0, 1]).visible(false);
    test_param_table.clear();
    test_param_table.rows.add(test_param);
    test_param_table.draw();
}

function submit_values() {
    document.getElementById('test_t').value = JSON.stringify(lab_test_cart);
}

$('#lab_tests_table tbody').on('click', '#delete_btn', function () {
    var index = lab_tests_table.row($(this).parents('tr')).index();
    var data = lab_tests_table.row($(this).parents('tr')).data();

    if (data.lab_test_order_id) {
        getExistingOrders(data.lab_test_order_id);
        return
    }

    lab_test_cart.splice(index, 1);
    drawLabTestTable();
    submit_values()

});



$('#lab_tests_table tbody').on('click', '#result_db_btn', function () {
    var data = lab_tests_table.row($(this).parents('tr')).data();

    url = urls.routes.printTestResults;
    url = url.replace('order_id', data.lab_test_order_id);
    let a = document.getElementById('printResultSheet');
    a.href = url;
    a.click();
});


$('#lab_tests_table tbody').on('click', '#edit_btn', function () {
    var index = lab_tests_table.row($(this).parents('tr')).index();
    var data = lab_tests_table.row($(this).parents('tr')).data();
    /* Reset modal tabs selection */
    $('#test_parameters').find('#payment_method_link').attr('aria-selected', false);
    $('#test_parameters').find('#payment_method_link').removeClass('active');
    $('#test_parameters').find('#payment_methods').removeClass('active show');
    $('#test_parameters').find('#parameters_link').attr('aria-selected', true);
    $('#test_parameters').find('#parameters_link').addClass('active');
    $('#test_parameters').find('#parameters_tab').addClass('active show');

    $('#Payments').val(JSON.stringify(data.price_category)).change();

    $('#test_parameters').find('#test_name').text('Edit ' + data.name);
    $('#test_parameters').find('#index').val(index);
    $('#test_parameters').modal('show');
    drawTestParamTable(data.parameters);
});


$('#test_param_table tbody').on('click', '#remove_btn', function () {

    let index = parseInt($('#index').val());
    let test_order_data = lab_tests_table.row(index).data();
    let parameter_table_data = test_param_table.row($(this).parents('tr')).data();

    if (test_order_data.parameters?.length <= 1) {

        notify('Parameter is Required', 'top', 'right', 'warning');
        return
    }

    test_order_data.parameters = test_order_data.parameters.filter(function (parameter) {
        return parameter.id != parameter_table_data.id;
    });

    drawTestParamTable(test_order_data.parameters);

    drawLabTestTable(lab_test_cart);

    submit_values()

});


//when the user clicks the save button in the parameter's modal
$('#test_parameters').on('click', '#saveInfo', function () {

    //get selected price category
    let priceCateg = document.getElementById("Payments").value;

    let index = parseInt($('#index').val());

    let test_order_data = lab_tests_table.row(index).data();
    test_order_data.price_category = JSON.parse(priceCateg);
    lab_tests_table.row(index).data(test_order_data).invalidate();  //renders the changed row
    submit_values();
    $('#test_parameters').modal('hide');


});

getExistingOrders();

function getExistingOrders(lab_test_order_id = null) {

    lab_test_cart = lab_test_cart.filter(function (cart_data) {
        return cart_data.lab_test_order_id == null;
    });

    var visit_id = document.getElementById('visitID').value;
    $.ajax({
        url: urls.routes.getExistingOrders,
        data: {
            'visit_id': visit_id,
            'lab_test_order_id': lab_test_order_id,
        },
        type: 'get',
        dataType: 'json',
        cache: false,
        success: function (data) {

            if (data.isPastVisit) {
                table_block = true;
            }

            let buttons = {
                delete: function (payment_status) {
                    let disabled = table_block || payment_status ? true : false;
                    return `<input type='button' id='delete_btn' ${disabled ? 'disabled' : ''} value='Delete' class='btn btn-${disabled ? 'secondary' : 'danger'} btn-rounded btn-sm' />`
                },

                edit: function (disabled = false) {
                    return `<input type='button' ${disabled ? 'disabled' : ''} value='Edit' class='btn btn-warning btn-rounded btn-sm' />`
                },

                result: function (result_status) {
                    return `<input type='button' id='result_db_btn' ${result_status ? 'disabled' : ''} value='Result' class='btn btn-success btn-rounded btn-sm' />`
                },
            }

            data.test_orders.forEach(function (order) {

                if (order.result_status) {
                    order.action = `${buttons.edit(true)} ${buttons.result()}`;
                } else {
                    order.action = `${buttons.edit(true)} ${$('#review').length ? buttons.delete(true) : buttons.delete(order.payment_status)}`
                }

                order.parameters = order.results.reduce(function (parameters, result) {
                    parameters.push({ id: result.lab_test_parameter_id, unit: null, name: null, lab_tests_id: order.lab_tests_id });
                    return parameters;
                }, []);

                addOrderToCart(order);
            });

            drawLabTestTable();
        }
    });
}


$('#service_check_modal').find('#add_btn').on('click', function () {
    addTestToCart();
    $('#service_check_modal').modal('hide');
});

$('#order_price_category').on('change', function () {

    if (!test_to_add) {
        return
    }

    let order_price_category = document.getElementById('order_price_category').value;
    order_price_category = JSON.parse(order_price_category);
    test_to_add.price_category = order_price_category;

    if (order_price_category.id != nhifPriceCategory) {
        $('#service_check_modal').find('#approval_ref_no_div').hide();
        $('#service_check_modal').find('.modal-dialog').removeClass('modal-lg');
        $('#service_check_modal').find('#parameters_to_add_div').hide();
        $('#service_check_modal').find('#approval_ref_no').attr('disabled', true);
        $('#service_check_modal').find('#add_btn').attr('disabled', false);
        $('#restriction_message').text('');
        return
    }

    if (test_to_add.grouped) {
        checkParameterRestriction();
        return;
    }

    if (!test_to_add.valid) {
        $('#service_check_modal').find('#add_btn').attr('disabled', true);
        return
    }

    if (test_to_add.require_approval) {
        $('#service_check_modal').find('#approval_ref_no_div').show();
        $('#service_check_modal').find('#approval_ref_no').attr('disabled', false);
        $('#service_check_modal').find('#add_btn').attr('disabled', true);
        verifyApprovalRefNo();
        return
    }
});



$('#approval_ref_no').on('change', function () {
    if (test_to_add.require_approval) {
        $('#service_check_modal').find('#add_btn').attr('disabled', true);
    }
    verifyApprovalRefNo($(this).val(), test_to_add.nhif_code, function (data) {
        if (!data.isValid) {
            test_to_add.valid_approval_ref = false;
            $('#restriction_message').text(data.message);
            $('#service_check_modal').find('#add_btn').attr('disabled', true);
            return;
        }
        if (data.isValid) {
            test_to_add.approval_ref_no = approval_ref_no;
            test_to_add.valid_approval_ref = true;
            $('#restriction_message').text(data.message);
            $('#service_check_modal').find('#add_btn').attr('disabled', false);
            return;
        }
    });
});



$('#parameters_to_add_table tbody').on('change', '.parameter_approval', function () {
    let parameter_table_data = parameters_to_add_table.row($(this).parents('tr')).data();
    let approval_ref_no = $(this).val();
    verifyApprovalRefNo(approval_ref_no, parameter_table_data.nhif_code, function (data) {

        test_to_add.parameters = test_to_add.parameters.map(function (parameter) {

            if (parameter.id != parameter_table_data.id) {
                return parameter;
            }
            if (!data.isValid) {
                parameter.approval_ref_no = approval_ref_no;
                parameter.valid_approval_ref = false;
                return parameter;
            }
            parameter.approval_ref_no = approval_ref_no;
            parameter.valid_approval_ref = true;
            return parameter;
        });
        drawParametersToAddTable();
    });
});

$('#service_check_modal').on('hidden.bs.modal', function () {
    test_to_add = null;
    selected = JSON.stringify(getPriceCategory(nhifPriceCategory));
    $('#order_price_category').val(selected).change();
});


$('#parameters_to_add_table tbody').on('click', '#remove_parameter_to_add', function () {
    let parameter_table_data = parameters_to_add_table.row($(this).parents('tr')).data();

    if (test_to_add.parameters?.length <= 1) {
        $('#service_check_modal').modal('hide');
        return
    }

    test_to_add.parameters = test_to_add.parameters.filter(function (parameter) {
        return parameter.id != parameter_table_data.id;
    });

    drawParametersToAddTable();
});

function isNotValidParameter(parameter) {
    return !parameter.valid
}

function dontRequireApproval(parameter) {
    return !parameter.require_approval;
}

function requireApproval(parameter) {

    if (!parameter.require_approval) {
        return false;
    }

    return !parameter.valid_approval_ref;
}

function hasItemCode(parameter) {
    return Boolean(parameter.nhif_code);
}

function doesntHaveItemCode(parameter) {
    return !hasItemCode(parameter);
}

function checkParameterRestriction() {

    if (!test_to_add) { return }

    $('#service_check_modal').find('.modal-dialog').addClass('modal-lg');
    $('#service_check_modal').find('#parameters_to_add_div').show();

    test_to_add.parameters = test_to_add.parameters.map(function (parameter) {
        parameter.require_approval = false;
        parameter.approval_ref_no = null;
        parameter.valid = true;
        parameter.valid_approval_ref = null;
        return parameter;
    });

    if (test_to_add.parameters.every(doesntHaveItemCode)) {
        drawParametersToAddTable();
        return
    }

    let item_codes = test_to_add.parameters.reduce(function (data, parameter) {
        if (doesntHaveItemCode(parameter)) {
            return data;
        }
        data.push(parameter.nhif_code);
        return data;
    }, []);

    let data = {
        scheme_id: schemeID,
        item_codes: item_codes.join(',')
    };

    function onSuccess(data) {
        test_to_add.parameters = test_to_add.parameters.map(function (parameter) {

            if (doesntHaveItemCode(parameter)) {
                return parameter;
            }

            let result = data.find(function (datum) {
                return datum.code == parameter.nhif_code;
            });

            if (!result.isValid) {
                parameter.valid = false;
                return parameter;
            }

            if (result.requireApproval) {
                parameter.require_approval = true;
                parameter.valid_approval_ref = false;
                return parameter;
            }

            return parameter;
        });

        if (test_to_add.parameters.some(isNotValidParameter)) {
            drawParametersToAddTable();
            return;
        }

        if (test_to_add.parameters.some(requireApproval)) {
            drawParametersToAddTable();
            return;
        }

        if (test_to_add.parameters.some(doesntHaveItemCode)) {
            drawParametersToAddTable();
            return;
        }

        addTestToCart();

    }
    checkSerivesRestriction(data, onSuccess);
}


function parameterCheckStatus() {

    $('#service_check_modal').modal('show');

    if (test_to_add.parameters.every(doesntHaveItemCode)) {
        $('#service_check_modal').find('#add_btn').attr('disabled', false);
        $('#restriction_message').text(
            'All parameters in a grouped tests are missing nhif item code, can\'t check if patient is eligible for these tests.'
        );
        return;
    }

    if (test_to_add.parameters.some(isNotValidParameter)) {
        $('#service_check_modal').find('#add_btn').attr('disabled', true);
        $('#restriction_message').text('Some parameters are not eligible for this patient or requires approval, please fill approval reference number or remove them.');
        return;
    }

    if (test_to_add.parameters.some(requireApproval)) {
        $('#service_check_modal').find('#add_btn').attr('disabled', true);
        $('#restriction_message').text('Some parameters are not eligible for this patient or requires approval, please fill approval reference number or remove them.');
        return;
    }

    if (test_to_add.parameters.some(doesntHaveItemCode)) {
        $('#service_check_modal').find('#add_btn').attr('disabled', false);
        let missing_nhif_code_names = test_to_add.parameters.reduce(function (names, parameter) {
            if (hasItemCode(parameter)) {
                return names;
            }
            names.push(parameter.name);
            return names;
        }, []);
        $('#restriction_message').text(
            `${missing_nhif_code_names.join(', ')} ${missing_nhif_code_names.length > 1 ? 'parameters' : 'parameter'} in a grouped tests ${missing_nhif_code_names.length > 1 ? 'are' : 'is'} missing nhif item code, can\'t check if patient is eligible for these tests.`
        );
        return;
    }

    $('#service_check_modal').find('#add_btn').attr('disabled', false);
    $('#restriction_message').text('');
    return;

}


function drawParametersToAddTable() {
    parameterCheckStatus();
    parameters_to_add_table.clear();
    parameters_to_add_table.rows.add(test_to_add.parameters);
    parameters_to_add_table.draw();
}


function checkTestRestriction() {

    if (!test_to_add) { return }
    // reset values
    $('#restriction_message').text('');
    $('#service_check_modal').find('#add_btn').attr('disabled', false);
    $('#service_check_modal').find('#approval_ref_no_div').hide();
    $('#service_check_modal').find('#approval_ref_no').attr('disabled', true);
    $('#service_check_modal').find('#approval_ref_no').val('');
    $('#service_check_modal').find('#require_approval').val('');
    $('#service_check_modal').find('#parameters_to_add_div').hide();
    $('#service_check_modal').find('.modal-dialog').removeClass('modal-lg');

    if (!nhifServiceEnabled) {
        addTestToCart();
        return
    }

    if (nhifPriceCategory != visitPriceCategory) {
        addTestToCart();
        return
    }

    if (!schemeID) {
        $('#service_check_modal').modal('show');
        $('#restriction_message').text('Scheme id missing for this patient');
        return
    }

    if (test_to_add.grouped) {
        checkParameterRestriction();
        return;
    }

    if (!test_to_add.nhif_code) {
        $('#service_check_modal').modal('show');
        $('#restriction_message').text(
            'NHIF code is missing in this test, can\'t check if patient is eligible for this service'
        );
        return
    }

    let data = {
        item_code: test_to_add.nhif_code,
        scheme_id: schemeID
    };

    // callback function after success service check
    function onSuccess(data) {
        if (!data.isValid) {
            test_to_add.valid = false;
            $('#service_check_modal').modal('show');
            $('#service_check_modal').find('#add_btn').attr('disabled', true);
            $('#restriction_message').text(data.message);
            return;
        }

        if (data.requireApproval) {
            test_to_add.require_approval = true;
            $('#service_check_modal').modal('show');
            $('#approval_ref_message').text(data.message);
            $('#service_check_modal').find('#add_btn').attr('disabled', true);
            $('#service_check_modal').find('#approval_ref_no_div').show();
            $('#service_check_modal').find('#approval_ref_no').attr('disabled', false);
            return
        }
        addTestToCart();
    }
    checkSerivesRestriction(data, onSuccess);
}


function checkSerivesRestriction(data, callback = null) {
    if (!data) {
        return
    }
    $.ajax({
        url: urls.routes.checkServiceRestriction,
        type: 'get',
        dataType: 'json',
        data: data,
        success: function (data) {
            if (typeof callback === 'function') {
                callback(data);
            }
        },
        error: function () {
            notify('Check service restriction failed', 'top', 'right', 'warning');
        }
    });
}

function verifyApprovalRefNo(approval_ref_no, item_code, callback) {

    if (!test_to_add) {
        return
    }

    let visit_id = document.getElementById('visitID').value;

    if (!item_code || !visit_id || !approval_ref_no) {
        return;
    }

    $('#restriction_message').text("VALIDATING REFERENCE NO");
    $('#loading_image_div').show();
    $.ajax({
        url: urls.routes.verifyApprovalRefNo,
        type: 'get',
        dataType: 'json',
        data: {
            item_code,
            visit_id,
            approval_ref_no
        },
        success: function (data) {
            if (typeof callback === 'function') {
                callback(data);
            }
        },
        complete: function () {
            $('#loading_image_div').hide();
        },
        error: function () {
            notify('Verifying reference number failed', 'top', 'right', 'warning');
        }
    });
}