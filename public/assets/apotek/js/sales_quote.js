let cart = []; //for data displayed.
let default_cart = []; //for default values.
let details = [];
let sale_items = [];
let edit_btn_set = 0;
let category_option = document.getElementById('category').value;
let tax = Number(document.getElementById("vat").value);
let container=[];

let cart_table = $('#cart_table').DataTable({
    searching: false,
    bPaginate: false,
    bInfo: false,
    ordering: false,
    data: cart,
    columns: [{
            title: "Product Name"
        },
        {
            title: "Strength"
        },
        {
            title: "Route"
        },
        {
            title: "Dosage"
        },
        {
            title: "Stock Qty",
            defaultContent: '0'
        },
        {
            title: "Frequency"
        },
        {
            title: "Duration"
        },
        {
            title: "Quantity"
        },
        {
            title: "productID"
        },
        {
            title: 'Status', render: function (value) {
                if (value == 1) {
                    return `<span class="badge badge-success">Taken</span>`;
                }
                if (value == 2) {
                    return `<span class="badge badge-secondary">Partial</span>`;
                }

                if (value == 0) {
                    return `<span class="badge badge-warning">Pending</span>`;
                }
                return `<span class="badge badge-danger">Pending</span>`;

            }
        },
        {
            title: "Taken"
        },
        {
            title: "App Ref No"
        },
        {
            title: "Require Approval"
        },
        {
            title: "Prescription ID"
        },
        {
            title: "Action", render: function (action, type, row) {
                action = `<div><input type='hidden' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input ${+row[9]? 'disabled':''} type='button' value='Delete'  id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/></div>`;

                return action;
            },
            defaultContent: "<div><input type='hidden' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/></div>"
        }
    ]
});
cart_table.columns([2, 4, 8, 10, 11, 12, 13]).visible(false); //this columns are just for manipulations

let prescription_detail_table = $('#fixed-header-price').DataTable({
    'columns': [{
            'data': 'product.name'
        },
        {
            'data': 'strength'
        },
        {
            'data': 'dosage'
        },
        {
            'data': 'frequency'
        },
        {
            'data': 'duration'
        },
        {
            'data': 'quantity'
        },
        {
            'data': 'status',  render: function (value) {
                if (value == 1) {
                    return `<span class="badge badge-success">Taken</span>`;
                }
                if (value == 2) {
                    return `<span class="badge badge-secondary">Partial</span>`;
                }

                return `<span class="badge badge-danger">Pending</span>`;

            }
        },
        {
            'data': 'action'
        },
    ],
    searching: false,
    bPaginate: false,
    bInfo: false
});


$('#fixed-header-price tbody').on('click', '#edit_quantity', function (event) {
    event.preventDefault();
    let row = prescription_detail_table.row($(this).parents('tr')).data();
    let data = prescription_detail_table.data();
    let index = prescription_detail_table.row($(this).parents('tr')).index();
    row.quantity = `<input type='number' min='0' class='form-control' id='edit_quantity_text' required' value='${row.quantity}' />`;
    row.action = "<button type='button' id='' class='btn btn-sm btn-rounded btn-primary' disabled>Edit</button>";
    data[index] = row;
    prescription_detail_table.clear();
    prescription_detail_table.rows.add(data);
    prescription_detail_table.draw();
    $('#fixed-header-price tbody').find('#edit_quantity_text').focus();
});
$('#fixed-header-price tbody').on('blur', '#edit_quantity_text', saveQuantity);
$('#fixed-header-price tbody').on('change', '#edit_quantity_text', saveQuantity);

function saveQuantity(event) {
    event.preventDefault();
    let row = prescription_detail_table.row($(this).parents('tr')).data();
    let data = prescription_detail_table.data();
    let index = prescription_detail_table.row($(this).parents('tr')).index();
    row.action = "<button type='button' id='edit_quantity' class='btn btn-sm btn-rounded btn-primary'>Edit</button>";
    row.quantity = event.target.value;
    data[index] = row;
    prescription_detail_table.clear();
    prescription_detail_table.rows.add(data);
    prescription_detail_table.draw();
    $.ajax({
        url: config.routes.updatePrescriptionQuatity,
        data: {
            "_token": config.token,
            "quantity": row.quantity,
            "id": row.id,
        },
        type: 'post',
        dataType: 'json',
        success: function () {
            notify('Quantity Updated Successfully!', 'top', 'right', 'success');

        },
    });
}

let prescription_filter_table = $('#fixed-header-prescription-filter').DataTable({
    'pageLength': 100,
    'columns': [{
            'data': 'visit.patient.registration_no'
        },
        {
            'data': 'id'
        },
        {
            'data': 'name'
        },
        {
            'data': 'visit.price_category.name', defaultContent: '-'
        },
        {
            'data': 'status'
        },
        {
            'data': 'date',
            render: function (date) {
                return moment(date).format('MMM DD,YYYY');
            }
        },
        {
            'data': 'action',
            defaultContent: "<button type='button' id='show_btn' class='btn btn-sm btn-rounded btn-success'>Show</button><button type='button' id='dispense_btn' class='btn btn-sm btn-rounded btn-primary'>Dispense</button><button id='print_btn' class='btn btn-sm btn-rounded btn-secondary'><span class='fa fa-print' aria-hidden='true'></span>Print</button>"
        }
    ],
    aaSorting: [
        [1, 'desc']
    ]
});
prescription_filter_table.columns([1]).visible(false); //this columns are just for manipulations


$("#products").on('change', function () {
    $('#myModal').find('#add_btn').attr('disabled', false);
    valueCollection();
});
$("#save_other_product").on('click', function (event) {
    event.preventDefault();
    valueCollection();
});

$("#generic_name").on('change', function () {
    var price_category_id = document.getElementById('price_category_id').value;
    var generic_name = document.getElementById('generic_name').value;

    /*make ajax call for more*/
    $.ajax({
        url: config.routes.selectProducts,
        data: {
            "_token": config.token1,
            "generic_name": generic_name,
            'id': price_category_id
        },
        type: 'post',
        dataType: 'json',
        success: function (result) {
            $("#products option").remove();
            $.each(result, function (detail, name) {
                $('#products').append($('<option>', {
                    value: detail,
                    text: name
                }));
            });
        },
    });

});

$('#cart_table tbody').on('click', '#delete_btn', function () {
    edit_btn_set = 0;
    let index = cart_table.row($(this).parents('tr')).index();
    let data = cart_table.row($(this).parents('tr')).data();

    // if exist delete it in db
    if (data[13]) {

        if (!window.confirm('Prescription is saved, confirm to delete it?')) {
            return
        }
        $.ajax({
            url: config.routes.deletePrescription,
            data: {
                prescription_detail_id: data[13],
                _token: config.token1,
            },
            type: 'post',
            dataType: 'json',
            success: function () {
                cart.splice(index, 1);
                default_cart.splice(index, 1);
                discount();
            }
        });

        return;
    }

    cart.splice(index, 1);
    default_cart.splice(index, 1);
    discount();
});

$('#deselect-all-quote').on('click', function () {
    edit_btn_set = 0;
    let cart_data = document.getElementById("order_cart").value;
    if (!(cart_data === '' || cart_data === 'undefined')) {
        let r = confirm('Cancel prescription?');
        if (r === true) {
            /*continue*/
            deselectQuote();
        } else {
            /*return false*/
            return false;
        }
    }
    // deselect();
});

$('#close_btn').on('click', function () {
    edit_btn_set = 0;
    let cart_data = document.getElementById("order_cart").value;
    if (!(cart_data === '' || cart_data === 'undefined')) {
        let r = confirm('Close prescription?');
        if (r === true) {
            /*continue*/
            deselectQuote();
        } else {
            /*return false*/
            return false;
        }
    }
    // deselect();
});

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;
        const negativeSign = amount < 0 ? "-" : "";
        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;
        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {}
}

function discount() {
    let dis = (document.getElementById("sale_discount").value);
    sale_discount = (parseFloat(dis.replace(/\,/g, ''), 10) || 0);
    let sub_total, total_vat, total = 0;
    let sale_quote_cart = [];
    // console.log(cart);

    let stringified_cart;

    if (cart[0]) {

        let reduced__obj_cart = {},
            incremental_cart;
        // console.log(cart);


        cart.forEach(function (item, index, arr) {
            let sale_qoute = {};

            sale_qoute.item_name = item[0];
            sale_qoute.strength = item[1];
            sale_qoute.route = item[2];
            sale_qoute.quantity = item[7];
            sale_qoute.dosage = item[3];
            sale_qoute.frequency = item[5];
            sale_qoute.duration = item[6];
            sale_qoute.product_id = item[8];
            sale_qoute.status = item[9];
            sale_qoute.quantity_taken = item[10];
            sale_qoute.approval_ref_no = item[11];
            sale_qoute.require_approval = item[12];
            sale_qoute.prescription_detail_id = item[13];
            sale_quote_cart.push(sale_qoute);

        });

        stringified_cart = JSON.stringify(sale_quote_cart);
    }

    document.getElementById("order_cart").value = stringified_cart;
    document.getElementById("price_cat").value = price_category;


    cart_table.clear();
    cart_table.rows.add(cart);
    cart_table.draw();
}

function valueCollection() {
    $('#edit_quantity').change();
    let cart_data = [];
    let bought_product = {};
    product = document.getElementById("products").value;
    let other_product = document.getElementById('other_product').value;
    document.getElementById("other_product").value = "";
    document.getElementById("products").value = "";


    if (product) {
        let selected_fields = product.split('#@');
        let item_name = selected_fields[0];
        let price = Number(selected_fields[1]);
        let productID = Number(selected_fields[2]);
        let qty = Number(selected_fields[3]);
        let strength = selected_fields[4];
        let dosage = selected_fields[5];
        let vat = Number((price * tax).toFixed(2));
        let unit_total = Number(price + vat);
        let frequency = selected_fields[6];
        let route = selected_fields[7];
        let duration = selected_fields[8];
        let quantity = selected_fields[9];
        let product_type = selected_fields[10] || 'medicine';
        let product_nhif_code = selected_fields[11];

        //set value in the modal
        document.getElementById("product_name").value = item_name;
        document.getElementById("strength").value = strength;
        document.getElementById("dosage").value = dosage || strength;
        document.getElementById("quantity_read").value = numberWithCommas(qty);
        document.getElementById("product_id").value = productID;
        document.getElementById("quantity").value = quantity || '';
        document.getElementById('product_type').value = product_type
        document.getElementById('nhif_item_code').value = product_nhif_code

        $('#route').val(route || 'Per Oral').change();
        $('#frequency').val(frequency || 'OD').change();
        $('#duration').val(duration || '').change();

        $("#strength, #dosage, #frequency, #route").attr('disabled', false);
        $("#strength, #dosage, #frequency, #route").parents('.form-group').show();
        if (product_type.toLowerCase() !== 'medicine') {
            $("#strength, #dosage, #frequency, #route").val('');
            $("#strength, #dosage, #frequency, #route").attr('disabled', true);
            $("#strength, #dosage, #frequency, #route").parents('.form-group').hide();
        }


        checkSerivesRestriction(product_nhif_code);

        // Get the modal
        let modal = document.getElementById("myModal");

        if (dosage !== '') {
            let frequency = document.getElementById('frequency').value;
            let duration = document.getElementById('duration').value;
            // calculateQuantity(dosage.toString().replace(/,/g, ''), frequency, duration);
        }

        function quantityWarning() {

            let quantity = document.getElementById('quantity').value;
            $('#quantity_error_warning').hide();
            $('#quantity_error_warning').text('');

            if (!quantity) {
                return;
            }
            if (+quantity > +qty) {
                $('#quantity_error_warning').text(`Quantity ${quantity} exceeds ${qty} of available stock`);
                $('#quantity_error_warning').show();
            }
        }

        quantityWarning();

        $('#quantity').on('change keyup', quantityWarning);

        modal.style.display = "block";

        $('#close_modal_btn').on('click', function () {
            modal.style.display = "none";
            $('#quantity').off('change keyup');
        });

        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
                $('#quantity').off('change keyup');
            }
        };
    } else if (other_product) {
        $('#route').val('Per Oral').change();
        $('#frequency').val('OD').change();
        $('#duration').val('').change();
        let item_name = other_product;
        let productID = Number(-1);
        let qty = Number(0);

        document.getElementById("product_name").value = item_name;
        document.getElementById("strength").value = '';
        document.getElementById("dosage").value = '';
        document.getElementById("quantity_read").value = numberWithCommas(qty);
        document.getElementById("product_id").value = productID;
        document.getElementById("quantity").value = '';
        document.getElementById("nhif_item_code").value = '';
        // Get the modal
        let modal = document.getElementById("myModal");
        modal.style.display = "block";

        $('#close_btn').on('click', function () {
            modal.style.display = "none";
        });

        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        };
    } else {
        notify('Please write product name to save', 'top', 'right', 'warning')
    }

}

$('#quantity').on('change', function () {
    let qty = document.getElementById("quantity").value;
    if (qty !== '') {
        document.getElementById('quantity').value =
            numberWithCommas(parseFloat(qty.replace(/\,/g, ''), 10));
    } else {
        document.getElementById('quantity').value = '';
    }
});

$('#add_btn').on('click', function () {
    $('#myModal').find('#add_btn').attr('disabled', true);
    // Get the modal
    let modal = document.getElementById("myModal");

    let item_name = document.getElementById("product_name").value;
    let strength = document.getElementById("strength").value;
    let dosage = document.getElementById("dosage").value;
    let quantity = document.getElementById("quantity").value;
    let route = document.getElementById('route').value;
    let frequency = document.getElementById('frequency').value;
    let duration_value = document.getElementById('duration').value;
    let duration = `${duration_value || 0} Days`;
    let product_id = document.getElementById('product_id').value;
    let quantity_inc = 1;
    let product_type = document.getElementById('product_type').value;
    let approval_ref_no = document.getElementById('approval_ref_no').value;
    let require_approval = document.getElementById('require_approval').value;
    let visit_id = $('#visit_').val();

    let item = [];
    let cart_data = [];
    container=[];

    if (product_type.toLowerCase() === 'medicine') {

        if (route === '' || dosage === '' || frequency === '') {
            notify('Please Fill Required Fields!', 'top', 'right', 'warning');
            return false;
        }

    }

    if  (quantity === '' || duration_value === '') {
        notify('Please Fill Required Fields!', 'top', 'right', 'warning');
        return false;
    }


    var check_cart = document.getElementById("order_cart").value;

    var check_cart_to_array;
    if (check_cart === 'undefined') {
        check_cart_to_array = [];
    } else if (check_cart === '') {
        check_cart_to_array = [];
    } else {
        check_cart_to_array = JSON.parse(check_cart);
    }

    // duplicate function check if cart contain item with same id and is status is not taken.
    function isDuplicate(item) {
        return item.product_id == product_id && item.status != 1;
    }

    // This check if product is order
    function productExist(item) {
        return item.product_id == product_id;
    }

    // ajax call to check if there is saved pending prescription.
    // this ensure that no duplicates across all consultation allocations
    $.ajax({
        url: config.routes.getPrescription,
        data: {
            product_id,
            visit_id,
        },
        type: 'get',
        dataType: 'json',
        success: function (data) {
            // if there is prescription.
            if (!$.isEmptyObject(data)) {
                notify('Product' + ' ' + item_name + ' already added!', 'top', 'right', 'warning');
                return;
            }

            if (check_cart_to_array.some(isDuplicate)) {
                notify('Product' + ' ' + item_name + ' already added!', 'top', 'right', 'warning');
                return;
            }

            container.push(item_name);
            container.push(strength||'-');
            container.push(route||'-');
            container.push(dosage||'-');
            container.push(quantity_inc);
            container.push(frequency||'-');
            container.push(duration);
            container.push(quantity);
            container.push(product_id);
            container.push(null);
            container.push(null);
            container.push(approval_ref_no);
            container.push(require_approval);
            container.push(null);
            default_cart.push(cart_data);
            modal.style.display = "none";

            // If product exist after checking duplicate and prompt to add it if exists.
            if (check_cart_to_array.some(productExist)) {
                $("#confirmModal").modal();
                $('#product_duplicate').text(item_name);
                return;
            }

            cart.push(container);
            discount();
        }
    });

});

$('#add_duplicate').on('click', function () {
    //confirms the product can be added as duplicate
    cart.push(container);
    discount();
    $("#confirmModal").modal('hide');
});

$('#close_modal_btn').on('click', function () {
    // Get the modal
    let modal = document.getElementById("myModal");
    modal.style.display = "none";
});


function deselect() {
    // document.getElementById("sales_form").reset();
    // rePopulateSelect2();
    // rePopulateSelect2Customer();
    $('#customer_id').val('').change();
    document.getElementById('sale_discount').value = 0.0;
    document.getElementById('sub_total').value = 0.0;
    document.getElementById('total_vat').value = 0.0;
    document.getElementById('total').value = 0.0;
    document.getElementById('sale_paid').value = 0.0;
    document.getElementById('change_amount').value = 0.0;

    sub_total = 0;
    total = 0;
    cart = [];
    order_cart = [];
    default_cart = [];
    discount();
}

function deselectQuote() {
    document.getElementById("quote_sale_form").reset();
    sub_total = 0;
    total = 0;
    cart = [];
    order_cart = [];
    default_cart = [];
    discount();
}

function prescriptionDetails(prescription_id) {
    /*make ajax call*/
    $.ajax({
        url: config.routes.prescriptionDetail,
        type: "get",
        dataType: "json",
        data: {
            prescription_id: prescription_id
        },
        success: function (data) {
            data.forEach(data => {
                if (data.product_id == -1) {
                    data.product = {
                        name: `<span class="text-danger">${data.other_product}</span>`
                    };
                }
                return data.action = "";
            });
            if (config.permission) {
                data.forEach(data => {
                    if (config.permission.ManagePrescription) {
                        if (data.status != 0) {
                            return data.action = "<button type='button' disabled class='btn btn-sm btn-rounded btn-primary'>Edit</button>";
                        } else {
                            return data.action = "<button type='button' id='edit_quantity' class='btn btn-sm btn-rounded btn-primary'>Edit</button>";
                        }
                    }
                });
            }
            prescription_detail_table.clear();
            prescription_detail_table.rows.add(data);
            prescription_detail_table.draw();
            $('#prescription_details').modal('show');
        }
    });
}

function loadPreviousPrescription(date) {
    if (date !== '') {
        $.ajax({
            url: config.routes.filterPrescriptionByDate,
            type: 'get',
            dataType: 'json',
            data: {
                date: date
            },
            success: function (data) {
                data = data.map(function (prescriptionData) {
                    prescriptionData.action = `<button type='button' id='show_btn' class='btn btn-sm btn-rounded btn-success'>Show</button>
                    <button type='button' id='dispense_btn' class='btn btn-sm btn-rounded btn-primary'>Dispense</button>
                    <button id='print_btn' class='btn btn-sm btn-rounded btn-secondary'><span class='fa fa-print' aria-hidden='true'></span>Print</button>
                    `
                    return prescriptionData;
                });
                prescription_filter_table.clear();
                prescription_filter_table.rows.add(data);
                prescription_filter_table.draw();

                document.getElementById('t-body-1').style.display = 'none';
                document.getElementById('t-body-2').style.display = 'block';

            }
        })
    }
}

$('#fixed-header-prescription-filter tbody').on('click', '#show_btn', function () {
    let row_data = prescription_filter_table.row($(this).parents('tr')).data();
    let index = prescription_filter_table.row($(this).parents('tr')).index();
    prescriptionDetails(row_data.id);
});

$('#fixed-header-prescription-filter tbody').on('click', '#dispense_btn', function () {
    let row_data = prescription_filter_table.row($(this).parents('tr')).data();
    let index = prescription_filter_table.row($(this).parents('tr')).index();

    let url = config.routes.dispense;
    url = url.replace('id', row_data.visit.patient.id);
    let a = document.getElementById('dispense_tag');
    a.href = `${url}?visit_id=${row_data.visit_id}`;
    a.click();
});

$('#fixed-header-prescription-filter tbody').on('click', '#print_btn', function () {
    let row_data = prescription_filter_table.row($(this).parents('tr')).data();
    let index = prescription_filter_table.row($(this).parents('tr')).index();

    let url = config.routes.prescriptionPrint;
    url = url.replace('id', row_data.id);
    let a = document.getElementById('print_tag');
    a.href = url;
    a.click();
});

$('#prescription-Table tbody').on('click', '#quote_details', function () {
    $('#quote-details').modal('show');
});



function storeLocally(id) {
    localStorage.setItem('sale_type', id);
}

function triggerSaleType() {
    let sale_type_id = localStorage.getItem('sale_type');
    if (sale_type_id) {
        $('#price_category').val(sale_type_id).change();
    }
}

$(document).ready(function () {
    triggerSaleType();
});

$(document).ready(function () {
    $("#products option").remove();
    // let id = $(this).val();
    // storeLocally(id);

    let visit_id = document.getElementById('visit_').value;
    let id = document.getElementById('price_category_id').value;
    var price_category_id = document.getElementById('price_category_id').value;
    var all_stores = document.getElementById('stock_from_all_stores')?.value;



    $('#products').select2({
        ajax: {
            url: config.routes.filterProductByWords,
            type: "get",
            delay: 250,
            dataType: "json",
            data: function (params) {
                var query = {
                    word: params.term,
                    all_stores: all_stores,
                    price_category_id: price_category_id
                }
                return query;
            },
            processResults: function (data, page) {
                data = Object.keys(data).reduce(function (array, key) {
                    array.push({
                        id: key,
                        text: data[key]
                    });
                    return array;
                }, []);
                return {
                    results: data
                }
            }
        },
        initSelection: function (element, callback) {
            let data = [];
            data.push({
                id: "",
                text: 'Select product from the list'
            });
            callback(data);
        },
    });

});


/*local storage of sale type*/

$('#quote_sale_form').on('submit', function (event, force_save) {

    let cart = document.getElementById("order_cart").value;
    $('#confirmation_modal').modal('hide');

    if ((cart === '' || cart === 'undefined') && !force_save) {
        // notify('Prescription list empty', 'top', 'right', 'warning');
        $('#confirmation_modal').modal('show');
        return false;
    }

    $('#save_btn').attr('disabled', true);
    $('#save_btn').text('Saving');

});


$('#yes_btn').on('click', function () {
    $('#quote_sale_form').trigger('submit', true);
});

function isNumberKey(evt, obj) {
    let charCode = (evt.which) ? evt.which : event.keyCode;
    let value = obj.value;
    let dotcontains = value.indexOf(".") !== -1;
    if (dotcontains)
        if (charCode === 46) return false;
    if (charCode === 46) return true;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));

}

function numberWithCommas(digit) {
    return String(parseFloat(digit)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function checkSerivesRestriction(item_code) {

    // reset values
    $('#restriction_message').text('');
    $('#myModal').find('#add_btn').attr('disabled', false);
    $('#myModal').find('#approval_ref_no_div').hide();
    $('#myModal').find('#approval_ref_no').attr('disabled', true);
    $('#myModal').find('#approval_ref_no').val('');
    $('#myModal').find('#require_approval').val('');

    if (!config.nhifServiceEnabled) {
        return
    }

    let price_category_id = document.getElementById('price_category_id').value;
    if (config.nhifPriceCategory != price_category_id) {
        return
    }

    if (!item_code) {
        $('#restriction_message').text('NHIF code is missing in this product, can\'t check if patient is eligible for this service');
       return
    }

    if (!config.schemeID) {
        $('#restriction_message').text('Scheme id missing for this patient');
        return
    }

    
    $.ajax({
        url: config.routes.checkServiceRestriction,
        type: 'get',
        dataType: 'json',
        data: {
            item_code,
            scheme_id: config.schemeID
        },
        success: function (data) {
            if (!data.isValid) {
                // $('#myModal').find('#add_btn').attr('disabled', true);
                $('#myModal').find('#require_approval').val(1);
                $('#restriction_message').text(data.message);
                return;
            }
            if (data.requireApproval) {
                $('#approval_ref_message').text(data.message);
                $('#myModal').find('#require_approval').val(1);
                // $('#myModal').find('#add_btn').attr('disabled', true);
                $('#myModal').find('#approval_ref_no_div').show();
                $('#myModal').find('#approval_ref_no').attr('disabled', false);
                return
            }
        },
        error: function () {
            notify('Check service restriction failed', 'top', 'right', 'warning');
        }
    });
}

$('#approval_ref_no').on('change', function () {

    let require_approval = $('#require_approval').val();
    let item_code = $('#nhif_item_code').val();
    let visit_id = $('#visit_').val();
    let approval_ref_no = $('#approval_ref_no').val();

    if (!require_approval || !approval_ref_no) {
        $('#myModal').find('#add_btn').attr('disabled', false);
        return
    }

    if (!item_code || !visit_id || !approval_ref_no) {
        return;
    }

    $('#restriction_message').text("VALIDATING REFERENCE NO");
    $('#myModal').find('#add_btn').attr('disabled', true);
    $('#loading_image_div').show();
    $.ajax({
        url: config.routes.verifyApprovalRefNo,
        type: 'get',
        dataType: 'json',
        data: {
            item_code,
            visit_id,
            approval_ref_no
        },
        success: function (data) {
            if (!data.isValid) {
                $('#restriction_message').text(data.message);
                $('#myModal').find('#add_btn').attr('disabled', true);
                return;
            }
            if (data.isValid) {
                $('#restriction_message').text(data.message);
                $('#myModal').find('#add_btn').attr('disabled', false);
                return;
            }
        },
        complete: function () {
            $('#loading_image_div').hide();
        },
        error: function () {
            notify('Verifying reference number failed', 'top', 'right', 'warning');
        }
    });
});