var lab_test_cart = [];
var test_to_add = null;
var parameter_cart = [];
var table_block = false;

var lab_tests_table = $('#lab_tests_table').DataTable({
    order: [
        [1, "asc"]
    ],
    // dom: 't',
    pageLength: 100,
    columns: [{
            title: "ID"
        },
        {
            title: "status"
        },
        {
            title: "Test Name"
        },
        {
            title: "Payment"
        },
        {
            title: "Approval Ref #"
        },
        {
            title: "Require Approval"
        },
        {
            title: "Action",
            defaultContent: "<input type='button' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/>"
        }

    ]

});

var test_param_table = $('#test_param_table').DataTable({
    ordering: false,
    dom: 't',
    pageLength: 100,
    columns: [{
            title: "TestID"
        },
        {
            title: "ParamID"
        },
        {
            title: "Name"
        },
        {
            title: "Unit"
        },
        {
            title: "Action",
            defaultContent: "<input type='button' value='Remove' id='remove_btn' class='btn btn-danger btn-rounded btn-sm'/>"
        }

    ]

});



$('#test').on('change', function() {
    collectValues();
});

function collectValues() {
    var exist = 0;
    var test = JSON.parse(document.getElementById("test").value);
    document.getElementById("test").value = '';
    //check if table not blocked
    if (table_block) {
        notify('You Cannot Add Tests to the Past Visit', 'top', 'right', 'danger');
        return;
    } 

    if (!Array.isArray(lab_test_cart)) {
        return;
    }
    //Checking if Test is existing
    lab_test_cart.forEach(function(test_item) {
        if (test_item[0] === test.id || test_item[0].imaging_tests_id === test.id) {
            exist = 1;
            notify('Imaging Test Exists', 'top', 'right', 'warning');
        }
    });

    if (exist == 1) {
        return;
    }

    test_to_add = test;
    checkSerivesRestriction();

}

function pushSelectedTest() {
    if(!test_to_add) {return}
    var selectedTest = [];
    parameter_count = 0;

    order_comment =

    // test.parameters.forEach(function(element) {
    //     var parameter = {};
    //     parameter.test_id = test.id;
    //     parameter.unit = element.unit;
    //     parameter.name = element.name;
    //     parameter.id = element.id;
    //     parameter_cart.push(parameter);
    //     parameter_count++;
    // });

    selectedTest.push(test_to_add.id);
    selectedTest.push('new');
    selectedTest.push(test_to_add.name);
    selectedTest.push(test_to_add.price_category);
    selectedTest.push(test_to_add.approval_ref_no);
    selectedTest.push(test_to_add.require_approval);
    if (test_to_add.result_type === "Text" || test_to_add.result_type === "Long Text") {
        //selectedTest.push("No Parameters");
        selectedTest.push("<input type='button' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/>");

    } 
    // else {
    //     selectedTest.push($('#pricecateg').text());
    //     // selectedTest.push(parameter_count + " " + "Parameters");
    // }
    lab_test_cart.push(selectedTest);
    drawLabTestTable(lab_test_cart);
    submit_values()
}


$('#submit_btn').click(function() {
    $('#submit_btn').attr('disabled', true);
    $('#issue').submit();
    return true;
});


function drawLabTestTable(lab_test_cart) {
    lab_tests_table.columns([0, 1, 5]).visible(false);
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
}

function drawTestParamTable(test_param) {
    test_param_table.columns([0, 1]).visible(false);
    test_param_table.clear();
    test_param_table.rows.add(test_param);
    test_param_table.draw();
}

function submit_values() {
    // document.getElementById('test_p').value = JSON.stringify(parameter_cart);
    document.getElementById('test_t').value = JSON.stringify(lab_test_cart);
}

$('#lab_tests_table tbody').on('click', '#delete_btn', function() {
    var data = lab_tests_table.row($(this).parents('tr')).data();
    var index = lab_tests_table.row($(this).parents('tr')).index();


    parameter_cart = parameter_cart.filter(function(parameter) {
        return parameter.test_id != data[0];
    }); //Removing Parameter

    lab_test_cart.splice(index, 1);
    drawLabTestTable(lab_test_cart);
    submit_values()

});



$('#lab_tests_table tbody').on('click', '#delete_db_btn', function() {
    var data = lab_tests_table.row($(this).parents('tr')).data();
    getExistingOrders(data[0].imaging_tests_id);

});

$('#lab_tests_table tbody').on('click', '#result_db_btn1', function() {
    var data = lab_tests_table.row($(this).parents('tr')).data();


});

$('#test_param_table tbody').on('click', '#remove_btn', function() {
    var data = test_param_table.row($(this).parents('tr')).data();

    thisTestParam = parameter_cart.filter(function(parameter) {
        return parameter.test_id === data[0];
    });

    if (thisTestParam.length > 1) {
        parameter_cart = parameter_cart.filter(function(parameter) {
            return parameter.id != data[1];
        }); //Removing Parameter
        checkParameter(data[0]);
        submit_values()
    } else {
        notify('Parameter is Required', 'top', 'right', 'warning');
    }



});

$('#lab_tests_table tbody').on('click', '#result_db_btn', function() {
    var data = lab_tests_table.row($(this).parents('tr')).data();

    // var url = '/laboratory/test-result-reports/results-printing/'+data[0].lab_tests_order_id+'/'+data[0].lab_tests_id;
    // document.getElementById("printResultSheet").href=url;
    //     document.getElementById("printResultSheet").click();

    url = urls.routes.printTestResults;
    url = url.replace('order_id', data[0].imaging_tests_order_id);
    url = url.replace('test_id', data[0].imaging_tests_id);
    let a = document.getElementById('printResultSheet');
    a.href = url;
    a.click();
});


$('#lab_tests_table tbody').on('click', '#edit_btn', function() {
    var index = lab_tests_table.row($(this).parents('tr')).index();
    var data = lab_tests_table.row($(this).parents('tr')).data();
    $('#test_parameters').modal('show');
    $('#test_parameters').find('#test_name').text(data[2]);
    $('#test_parameters').find('#index').val(index);
    // checkParameter(data[0]);
    submit_values()


});

$('#test_parameters').on('click', '#saveInfo', function() {

    //get selected price category
    let priceCateg=document.getElementById("Payments").value;
    
    let index=parseInt($('#index').val());

    let x=lab_tests_table.row(index).data();
    
     x[3]= JSON.parse(priceCateg).name; //sets the new category
     lab_tests_table.row(index).data(x).invalidate();  //renders the changed row
     submit_values()
     $('#test_parameters').modal('hide');


});

function checkParameter(test_id) {
    var test_param = [];
    var parameter_count = 0;
    params = parameter_cart.filter(function(parameter) {
        return parameter.test_id == test_id;
    }); //Specific Test Params

    params.forEach(function(param) {
        var data = [];
        data.push(param.test_id);
        data.push(param.id);
        data.push(param.name);
        data.push(param.unit);
        test_param.push(data);
    });

    index = Number(document.getElementById('index').value);
    test_param.forEach(function(data) {
        parameter_count++;
    });

    lab_test_cart[index][3] = parameter_count + " " + "Parameters";

    drawTestParamTable(test_param);
    drawLabTestTable(lab_test_cart);
    submit_values()
}


getExistingOrders();

function getExistingOrders(test_id) {
    lab_test_cart = lab_test_cart.filter(function(cart_data) {
        return cart_data[1] === 'new';
    });
    var visit_id = document.getElementById('visitID').value;
    $.ajax({
        url: urls.routes.getExistingOrders,
        data: {
            'visit_id': visit_id,
            'test_id': test_id,
        },
        type: 'get',
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.status) {
                table_block = true;
            } else {
                data.forEach(function(data) {
                    var ids = {};
                    ids.imaging_tests_order_id = data.imaging_tests_order_id;
                    ids.imaging_tests_id = data.imaging_tests_id;
                    var pre_selected_test = [];
                    pre_selected_test.push(ids); //this is used on result checking
                    pre_selected_test.push('old');
                    pre_selected_test.push(data.tests.name);
                    pre_selected_test.push(data.payment_category.name);
                    pre_selected_test.push(data.approval_ref_no);
                    pre_selected_test.push(true);
                    //pre_selected_test.push(data.parameter + " Parameters");
                    if (data.status) {
                        table_block = true;
                        if (data.result_status != 'Pending') {
                            pre_selected_test.push("<input type='button' value='Edit' disabled='true' class='btn btn-warning btn-rounded btn-sm'/><input type='button' value='Result' id='result_db_btn' class='btn btn-success btn-rounded btn-sm'/>");
                        } else if (data.bill_status) {
                            pre_selected_test.push("<input type='button' value='Edit' disabled='true' class='btn btn-warning btn-rounded btn-sm'/><input type='button' disabled='true' value='Delete' class='btn btn-secondary btn-rounded btn-sm'/>");
                        }
                    } else {
                        table_block = false;

                        if (data.result_status != 'Pending') {
                            pre_selected_test.push("<input type='button' value='Result' id='result_db_btn' class='btn btn-success btn-rounded btn-sm'/>");
                        } else if (data.bill_status) {
                            pre_selected_test.push("<input type='button' value='Edit' disabled='true' class='btn btn-warning btn-rounded btn-sm'/><input type='button' disabled='true' value='Delete' class='btn btn-secondary btn-rounded btn-sm'/>");
                        } else {
                            if ($('#review').length) {
                                pre_selected_test.push("<input type='button' value='Edit' disabled='true' class='btn btn-warning btn-rounded btn-sm'/><input type='button' value='Delete' disabled='true' class='btn btn-danger btn-rounded btn-sm'/>");
                            } else {
                                pre_selected_test.push("<input type='button' value='Edit' disabled='true' class='btn btn-warning btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_db_btn' class='btn btn-danger btn-rounded btn-sm'/>");
                            }
                        }


                    }


                    lab_test_cart.push(pre_selected_test)
                })
            }

            drawLabTestTable(lab_test_cart)


        }
    });
}

$('#service_check_modal').find('#add_btn').on('click', function() {
    pushSelectedTest();
    $('#service_check_modal').modal('hide');
    test_to_add = null;
});

$('#order_price_category').on('change', function() {
    if (!test_to_add) {
        return
    }

    let order_price_category = document.getElementById('order_price_category').value;
    order_price_category = JSON.parse(order_price_category);
    test_to_add.price_category = order_price_category.name;

    if (order_price_category.id != nhifPriceCategory) {
        $('#service_check_modal').find('#approval_ref_no_div').hide();
        $('#service_check_modal').find('#approval_ref_no').attr('disabled', true);
        $('#service_check_modal').find('#add_btn').attr('disabled', false);
        return
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

$('#approval_ref_no').on('change', function() {
    verifyApprovalRefNo();
});

$('#service_check_modal').on('hidden.bs.modal', function() {
    test_to_add = null;
    // price category options
    let price_options = Array.from(document.getElementById('order_price_category')?.options);

    /* Options values are stringfied object and nhifPriceCategory is id  of category
        to set selected we have to get option value with equal ids
        */
    let select_option = price_options.reduce(function(selected, current) {
        let current_value = JSON.parse(current.value) ?? null;
        return current_value?.id == nhifPriceCategory ? current.value : selected
    }, null);


    $('#order_price_category').val(select_option).change();
});


function checkSerivesRestriction() {

    let order_price_category = document.getElementById('order_price_category').value;
    order_price_category = JSON.parse(order_price_category);
    test_to_add.price_category = order_price_category.name;
    test_to_add.require_approval = false;
    test_to_add.approval_ref_no = null;
    test_to_add.valid = true;
    // reset values
    $('#restriction_message').text('');
    $('#service_check_modal').find('#add_btn').attr('disabled', false);
    $('#service_check_modal').find('#approval_ref_no_div').hide();
    $('#service_check_modal').find('#approval_ref_no').attr('disabled', true);
    $('#service_check_modal').find('#approval_ref_no').val('');
    $('#service_check_modal').find('#require_approval').val('');

    if (!nhifServiceEnabled) {
        pushSelectedTest();
        return
    }

    if (nhifPriceCategory != visitPriceCategory) {
        pushSelectedTest();
        return
    }

    if (!test_to_add.nhif_code) {
        $('#service_check_modal').modal('show');
        $('#restriction_message').text(
            'NHIF code is missing in this test, can\'t check if patient is eligible for this service'
        );
        return
    }

    if (!schemeID) {
        $('#service_check_modal').modal('show');
        $('#restriction_message').text('Scheme id missing for this patient');
        return
    }


    $.ajax({
        url: urls.routes.checkServiceRestriction,
        type: 'get',
        dataType: 'json',
        data: {
            item_code: test_to_add.nhif_code,
            scheme_id: schemeID
        },
        success: function(data) {

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
            pushSelectedTest();
        },
        error: function() {
            notify('Check service restriction failed', 'top', 'right', 'warning');
        }
    });
}

function verifyApprovalRefNo() {
    if (!test_to_add) {
        return
    }

    let item_code = test_to_add.nhif_code;
    let visit_id = document.getElementById('visitID').value;
    let approval_ref_no = document.getElementById('approval_ref_no').value;

    if (test_to_add.require_approval) {
        $('#service_check_modal').find('#add_btn').attr('disabled', true);
    }

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
        success: function(data) {
            if (!data.isValid) {
                $('#restriction_message').text(data.message);
                $('#service_check_modal').find('#add_btn').attr('disabled', true);
                return;
            }
            if (data.isValid) {
                test_to_add.approval_ref_no = approval_ref_no;
                $('#restriction_message').text(data.message);
                $('#service_check_modal').find('#add_btn').attr('disabled', false);
                return;
            }
        },
        complete: function() {
            $('#loading_image_div').hide();
        },
        error: function() {
            notify('Verifying reference number failed', 'top', 'right', 'warning');
        }
    });
}
