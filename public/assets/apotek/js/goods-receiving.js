var cart = [];
let invoice_cart = [];
var default_cart = [];
var received_cart = [];
var details = [];
var order_items = [];
var product_ids = 0;
var edit_btn_set = 0;
var selected_name;

var items_table = $('#items_table').DataTable({
    searching: true,
    bPaginate: true,
    bInfo: true,
    data: order_items,
    columns: [
        { title: "ProductID" },
        { title: "Product Name" },
        {
            title: "Quantity",
            render: function(data) {
                return numberWithCommas(data);
            }
        },
        {
            title: "Price",
            render: function(data) {
                return formatMoney(data);
            }
        },
        {
            title: "VAT",
            render: function(data) {
                return formatMoney(data);
            }
        },
        {
            title: "Amount",
            render: function(data) {
                return formatMoney(data);
            }
        },
        { title: "Supplier" },
        { title: "OrderItemId" },
        {
            title: "Action",
            defaultContent: "<input type='button' value='Receive' id='receive_btn' class='btn btn-warning btn-rounded btn-sm' size='2' />"
        }
    ]
});

var order_table = $('#fixed-header-2').DataTable({
    aaSorting: [
        [6, 'desc']
    ]
});

var cart_table = $('#cart_table').DataTable({
    searching: false,
    bPaginate: false,
    bInfo: false,
    data: cart,
    columns: [
        { title: "Item Name" },
        { title: "Quantity" },
        {
            title: "Action",
            defaultContent: "<div><input type='button' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/></div>"
        }
    ]
});

$('#cart_table tbody').on('click', '#edit_btn', function() {
    var quantity;
    if (edit_btn_set === 0) {
        var row_data = cart_table.row($(this).parents('tr')).data();
        var index = cart_table.row($(this).parents('tr')).index();
        quantity = row_data[1];
        row_data[1] = "<input style='width: 45%' type='number'class='form-control' id='edit_quantity' required/>";
        cart[index] = row_data;
        cart_table.clear();
        cart_table.rows.add(cart);
        cart_table.draw();
        document.getElementById("edit_quantity").value = quantity;

        edit_btn_set = 1;

    }

});


$('#cart_table tbody').on('change', '#edit_quantity', function() {
    edit_btn_set = 0;
    var row_data = cart_table.row($(this).parents('tr')).data();
    var index = cart_table.row($(this).parents('tr')).index();
    row_data[1] = document.getElementById("edit_quantity").value;
    item_received.quantity = row_data[1];
    cart[index] = row_data;
    cart_table.clear();
    cart_table.rows.add(cart);
    cart_table.draw();

    item_receiveds = JSON.stringify(item_received);
    document.getElementById("received_cart").value = item_receiveds;

});

$('#cart_table tbody').on('click', '#delete_btn', function() {
    edit_btn_set = 0;
    var index = cart_table.row($(this).parents('tr')).index();
    // valuesCollection();
    cart.splice(index, 1);
    cart_table.clear();
    cart_table.rows.add(cart);
    cart_table.draw();
    document.getElementById("received_cart").value = JSON.stringify(cart);
    document.getElementById('buy_price').value = '0';
    document.getElementById('sell_price_id').value = '0';


});


//INVOICE RECEIVING

function goodReceivingFilterInvoiceBySupplier() {
    var supplier = document.getElementById('good_receiving_supplier_ids');
    var supplier_id = supplier.options[supplier.selectedIndex].value;

    /*ajax filter invoice by supplier*/
    $.ajax({
        url: config.routes.filterBySupplier,
        type: "get",
        dataType: "json",
        data: {
            supplier_id: supplier_id
        },
        success: function(data) {
            $('#goodreceving_invoice_id').prop('disabled', false);
            $("#goodreceving_invoice_id option").remove();
            $('#goodreceving_invoice_id').append($('<option>', {
                value: '',
                text: 'Select Invoice...',
                disabled: true,
                selected: true
            }));
            $.each(data, function(id, detail) {
                var datas = [detail.id];

                $('#goodreceving_invoice_id').append($('<option>', { value: datas, text: detail.invoice_no }));
            });
        },
        complete: function() {
            // $('#loading').hide();
        }
    });

}

// InvoiceCartTable
var invoicecart_table = $('#invoicecart_table').DataTable({
    searching: false,
    bPaginate: false,
    bInfo: false,
    ordering: false,
    columns: [
        { title: "Item Name", data: 'name' },
        { title: "Quantity", data: 'quantity' },
        { title: "Buy Price", data: 'buying_price' },
        { title: "Sell Price", data: 'selling_price' },
        { title: "Expire  Date", data: 'expire_date' },
        {
            title: "Action",
            defaultContent: "<div><input type='button' value='Edit' id='edit_btn' class='btn btn-info btn-rounded btn-sm'/><input type='button' value='Delete' id='delete_btn' class='btn btn-danger btn-rounded btn-sm'/></div>"
        }
    ]
});

$('#invoicecart_table tbody').on('click', '#edit_btn', function() {
    let quantity, buying_price, selling_price, expire_date;
    if (edit_btn_set === 0) {
        var row_data = invoicecart_table.row($(this).parents('tr')).data();
        var index = invoicecart_table.row($(this).parents('tr')).index();
        quantity = row_data.quantity;
        console.log(row_data.selling_price);
        buying_price = row_data.buying_price.toString().replace(',', '');
        selling_price = row_data.selling_price.toString().replace(',', '');
        expire_date = row_data.expire_date;
        let tommorow = moment().add(1, 'days').format("YYYY-MM-DD");
        console.log(selling_price);
        row_data.quantity = `<input style='width: 90%' type='text' class='form-control inventedAction' id='edit_quantity' value=${quantity}  required/>`;
        row_data.buying_price = `<input style='width: 90%' type='text' class='form-control inventedAction' id='edit_buying_price' onchange='invoiceamountCheck()'  value=${buying_price}  required/>`;
        row_data.selling_price = `<input style='width: 90%' type='text' class='form-control inventedAction' id='edit_selling_price' onchange='invoiceamountCheck()'  value=${selling_price}  required/>`;
        row_data.expire_date = `<input style='width: 90%' type='date' class='form-control' id='edit_expire_date' min="${tommorow}" value="${expire_date}" required/>`;
        invoice_cart[index] = row_data;
        invoicecart_table.clear();
        invoicecart_table.rows.add(invoice_cart);
        invoicecart_table.draw();

        edit_btn_set = 1;

    }

});






$('#invoicecart_table tbody').on('change', 'input.inventedAction', function() {
    edit_btn_set = 0;
    var row_data = invoicecart_table.row($(this).parents('tr')).data();
    var index = invoicecart_table.row($(this).parents('tr')).index();

    row_data.quantity = document.getElementById("edit_quantity").value;
    row_data.buying_price =  document.getElementById("edit_buying_price").value;
    row_data.selling_price = document.getElementById("edit_selling_price").value;
    // row_data.expire_date = document.getElementById("edit_expire_date").value;
    console.log(document.getElementById("edit_expire_date").value);

    let tommorow = moment().add(1, 'days').format("YYYY-MM-DD");
    let expire_date = '';
    let check_expire_date =  `<input style='width: 90%' type='date' class='form-control' id='edit_expire_date' min="${tommorow}" value=${expire_date} required/>`;
    console.log(check_expire_date);
    if ( row_data.expire_date == check_expire_date ) {

        row_data.expire_date = expire_date;
    } else {

        row_data.expire_date = document.getElementById("edit_expire_date").value;
    }


    invoice_cart[index] = row_data;
    invoicecart_table.clear();
    invoicecart_table.rows.add(invoice_cart);
    invoicecart_table.draw();

    invoice_cart_receiveds = JSON.stringify(invoice_cart);
    document.getElementById("invoice_received_cart").value = invoice_cart_receiveds;
    console.log(invoice_cart_receiveds);

    totalCostCalculated();

});

$('#invoicecart_table tbody').on('change', '#edit_expire_date', function () {
    edit_btn_set = 0;
    var row_data = invoicecart_table.row($(this).parents('tr')).data();
    var index = invoicecart_table.row($(this).parents('tr')).index();
    let check_expire_date = moment().format("YYYY-MM-DD");

    if ( document.getElementById("edit_expire_date").value == check_expire_date ) {

        $('#invoicesave_id').attr('disabled', true);
    } else {

        $('#invoicesave_id').attr('disabled', false);
    }
    

    row_data.expire_date = document.getElementById("edit_expire_date").value;
    console.log(document.getElementById("edit_quantity").value);
    row_data.quantity =document.getElementById("edit_quantity").value;
    console.log(row_data.quantity);
    row_data.buying_price =  formatMoney(document.getElementById("edit_buying_price").value);
    row_data.selling_price = formatMoney(document.getElementById("edit_selling_price").value);

    invoice_cart[index] = row_data;
    invoicecart_table.clear();
    invoicecart_table.rows.add(invoice_cart);
    invoicecart_table.draw();

    invoice_cart_receiveds = JSON.stringify(invoice_cart);
    document.getElementById("invoice_received_cart").value = invoice_cart_receiveds;
    // console.log(invoice_cart_receiveds);

    totalCostCalculated();

});

$('#invoicecart_table tbody').on('click', '#delete_btn', function() {
    edit_btn_set = 0;
    var index = invoicecart_table.row($(this).parents('tr')).index();
    invoice_cart.splice(index, 1);
    invoicecart_table.clear();
    invoicecart_table.rows.add(invoice_cart);
    invoicecart_table.draw();

    invoice_cart_receiveds = JSON.stringify(invoice_cart);
    document.getElementById("invoice_received_cart").value = invoice_cart_receiveds;
    console.log(invoice_cart_receiveds);
    totalCostCalculated();
    // enable Price Category DropDown If there is no product in the Invoice_cart
    if (invoice_cart.length === 0) {
        var disabled = $("#invoiceprice_category").attr('disabled');
        if (disabled) {
            $("#invoiceprice_category").removeAttr('disabled');
        }
    }

});

$("#invoiceselected-product").on("change", function() {

    let supplier = document.getElementById('good_receiving_supplier_ids');
    let supplier_id = supplier.options[supplier.selectedIndex].value;

    if(supplier_id === ""){

        notify('Please Select Supplier', 'top', 'right', 'danger');
        $('#invoiceselected-product option').prop('selected', function() {
            return this.defaultSelected;
        });

    } else {

        var disabled = $("#invoiceprice_category").attr('disabled');
        if (disabled === undefined) {
            $("#invoiceprice_category").attr('disabled', 'disabled');
        }

        invoicevaluesCollection();
    }



});


var invoice_item_received = {};
var invoice_cart_received = [];

function invoicevaluesCollection(qty) {

    qty = (qty || 1);
    var item = {};
    product = document.getElementById("invoiceselected-product").value;
    if(!product){
        return;
    }

    var selected_fields = product.split('#@');
    // console.log(selected_fields)
    var item_name = selected_fields[0];
    var product_id = selected_fields[1];
    let selling_price = 0;
    let buying_price = 0;

    /*set the global variable*/
    product_ids = product_id;
    selected_name = item_name;
    invoice_item_received.name = selected_fields[0];
    invoice_item_received.id = selected_fields[1];
    invoice_item_received.quantity = qty;
    document.getElementById("pr_id").value = formatMoney(selected_fields[2]);


    // Get Prices Depend On Category
    var price_category = document.getElementById("invoiceprice_category").value;
    var e = document.getElementById("good_receiving_supplier_ids");
    var supplier_id = e.options[e.selectedIndex].value;

    function update_price(data) {
        // Format Buying And Selling Prices if there is no data returned
        buying_price = formatMoney(data.length !== 0 ? data[0]['unit_cost'] : 0);
        selling_price = formatMoney(data.length !== 0 ? data[0]['price'] : 0);
        item.name = item_name;
        item.id = product_id;
        item.quantity = qty;
        item.selling_price = selling_price;
        item.buying_price = buying_price;
        item.expire_date = '';


        if (invoice_cart.some(function(element) {
                return element.id == item.id;
            })) {
            console.log('element id = item id');
            invoice_cart = invoice_cart.map(function(element) {
                if (element.id == item.id) {
                    element.quantity = +element.quantity + item.quantity;
                    invoice_item_received.quantity = element.quantity;
                }
                return element;
            })
            totalCostCalculated();
        } else {
            console.log('element id != item id');
            invoice_cart.push(item)
            totalCostCalculated();
        }
        invoicecart_table.clear();
        invoicecart_table.rows.add(invoice_cart);
        invoicecart_table.draw();
        // totalCostCalculated(selling_price, buying_price, qty );
        // For Ajax Call on Save A Form
        invoice_cart_received = invoice_cart;
        invoice_cart_receiveds = JSON.stringify(invoice_cart_received);
        document.getElementById("invoice_received_cart").value = invoice_cart_receiveds;
        document.getElementById("price_category_for_all").value = price_category;
        console.log(invoice_cart_receiveds);
    }

    getInvoiceItemPrice(product_id, price_category, supplier_id, update_price);


}

function getInvoiceItemPrice(product_id, price_category, supplier_id, call_back) {

    $.ajax({
        url: config.routes.invoiceItemPrice,
        type: "get",
        dataType: "json",
        data: {
            'product_id': product_id,
            'price_category': price_category,
            'supplier_id': supplier_id
        },
        success: function(data) {

            if (call_back && typeof call_back == 'function') {
                call_back(data);
                $('#invoiceselected-product').val('').change();
                return;
            }

            if (data.length === 0) {
                /*empty*/
                $("#sell_price_id").val(formatMoney('0'));
                $("#buy_price").val(formatMoney('0'));
            } else {
                $("#buy_price").val(formatMoney(data[0]['unit_cost']));
                $("#sell_price_id").val(formatMoney(data[0]['price']));
            }
            $('#invoiceselected-product').val('').change();
        }

    });
}

function totalCostCalculated(){
    var  sub_total = 0;
    var  total_buying_price = 0;
    var  total_selling_price = 0;
    var  selling_price = 0;
    var  buying_price = 0;
    var  price_selling_for_single_item = 0;
    var  price_buying_for_single_item = 0;

    console.log(invoice_cart);
    invoice_cart.forEach(function (item) {
        selling_price = parseFloat(item.selling_price.replace(/\,/g, ''), 10);
        buying_price = parseFloat(item.buying_price.replace(/\,/g, ''), 10);
        price_selling_for_single_item = (selling_price * item.quantity);
        price_buying_for_single_item = (buying_price * item.quantity);

        total_selling_price += price_selling_for_single_item;
        total_buying_price += price_buying_for_single_item;

     });

    sub_total = (total_selling_price - total_buying_price);


    document.getElementById("total_selling_price").value = formatMoney(total_selling_price);
    document.getElementById("total_buying_price").value = formatMoney(total_buying_price);
    document.getElementById("sub_total").value = formatMoney(sub_total);
}


function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
        console.log(e)
    }
}

function isNumberKey(evt, obj) {

    var charCode = (evt.which) ? evt.which : event.keyCode;
    var value = obj.value;
    var dotcontains = value.indexOf(".") !== -1;
    if (dotcontains)
        if (charCode === 46) return false;
    if (charCode === 46) return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

$('#selected-product').on('change', function() {
    valuesCollection();
});


var item_received = {};

function valuesCollection(qty) {
    qty = (qty || 1);
    var item = [];
    var cart_data = [];
    product = document.getElementById("selected-product").value;
    document.getElementById("selected-product").value = '';
    var selected_fields = product.split('#@');
    var item_name = selected_fields[0];
    var product_id = selected_fields[1];
    /*set the global variable*/
    product_ids = product_id;
    selected_name = item_name;
    item_received.name = selected_fields[0];
    item_received.id = selected_fields[1];
    document.getElementById("pr_id").value = formatMoney(selected_fields[2]);

    var price_category = document.getElementById("price_category").value;
    var e = document.getElementById("supplier_ids");
    var supplier_id = e.options[e.selectedIndex].value;

    getPrice(product_id, price_category, supplier_id);
    item_received.quantity = qty;
    item.push(item_name);
    item.push(qty);
    cart[0] = item;
    cart_table.clear();
    cart_table.rows.add(cart);
    cart_table.draw();

    item_receiveds = JSON.stringify(item_received);
    document.getElementById("received_cart").value = item_receiveds;
    console.log(item_receiveds);
}

$('#cancel-all').on('click', function() {
    deselect();
});

function getPrice(product_id, price_category, supplier_id, call_back) {

    $.ajax({
        url: config.routes.goodsreceiving,
        type: "get",
        dataType: "json",
        data: {
            'product_id': product_id,
            'price_category': price_category,
            'supplier_id': supplier_id
        },
        success: function(data) {

            if (call_back && typeof call_back == 'function') {
                call_back(data);
                return;
            }

            if (data.length === 0) {
                /*empty*/
                $("#sell_price_id").val(formatMoney('0'));
                $("#buy_price").val(formatMoney('0'));
            } else {
                $("#buy_price").val(formatMoney(data[0]['unit_cost']));
                $("#sell_price_id").val(formatMoney(data[0]['price']));
            }
        }

    });
}

function deselect() {
    edit_btn_set = 0;
    cart = [];
    cart_table.clear();
    cart_table.rows.add(cart);
    cart_table.draw();
    // document.getElementById("received_cart").value = JSON.stringify(cart);

}

function retrieveInvoiceBySupplier(supplier_id) {
    $.ajax({
        url: config.routes.filterBySupplier,
        type: "get",
        dataType: "json",
        data: {
            supplier_id: supplier_id
        },
        success: function(data) {
            $('#invoice_ids').prop('disabled', false);
            $("#invoice_ids option").remove();
            $('#invoice_ids').append($('<option>', {
                value: '',
                text: 'Select Invoice...',
                disabled: true,
                selected: true
            }));
            $.each(data, function(id, detail) {
                var datas = [detail.id];

                $('#invoice_ids').append($('<option>', { value: datas, text: detail.invoice_no }));
            });
        },
        complete: function() {
            // $('#loading').hide();
        }
    });
}


function orderReceive(items, supplier) {

    localStorage.setItem("order_id", items[0].order_id);
    localStorage.setItem("supplier", items);
    retrieveInvoiceBySupplier(supplier);
    localStorage.setItem("back", '');


    received = "<span class='badge badge-success'>Received</span>";
    var receive_cart = [];
    document.getElementById('purchases').style.display = 'none';
    order_items = [];

    items.forEach(function(item) {
        var item_data = [];
        item_data.push(item.product_id);
        item_data.push(item.name);
        item_data.push(item.quantity);
        item_data.push(item.price);
        item_data.push(item.vat);
        item_data.push(item.amount);
        if (supplier) {
            item_data.push(supplier);
            item_data.push(item.order_item_id);
            if (item.item_status === "Received") {
                item_data.push(received);
            }
            items_table.column(8).visible(true);
        } else {
            item_data.push('Preview');
            item_data.push(item.order_item_id);
            items_table.column(8).visible(false);
        }

        order_items.push(item_data);
    });

    items_table.clear();
    items_table.rows.add(order_items);
    items_table.columns([0, 6, 7]).visible(false);
    items_table.draw();
    document.getElementById('items').style.display = 'block';

    $('#cancel').on('click', function() {
        receive_cart = [];
        localStorage.setItem("back", 'back');
        localStorage.setItem("items", JSON.stringify(''));
        localStorage.setItem("supplier", '');

        document.getElementById('purchases').style.display = 'block';
        document.getElementById('items').style.display = 'none';
    });

}

$('#fixed-header-2 tbody').on('click', '#receive_order_btn', function() {
    var index = order_table.row($(this).parents('tr')).index();
    var data = order_table.row($(this).parents('tr')).data();
});

let remove_row_index;
$('#items_table tbody').on('click', '#receive_btn', function() {
    var index = items_table.row($(this).parents('tr')).index();
    var data = items_table.row($(this).parents('tr')).data();
    remove_row_index = index;

    $.ajax({
        url: config.routes.filterPrice,
        type: "get",
        dataType: "json",
        data: {
            product_id: data[0],
            supplier_id: data[6]
        },
        success: function(data) {
            $('#receive').find('.modal-body #sell_price_i').val(formatMoney(data.unit_cost));
        },
        complete: function() {
            // $('#loading').hide();
        }
    });

    $('#receive').modal('show');
    $('#receive').find('.modal-body #rec_qty').val(numberWithCommas(data[2]));
    $('#receive').find('.modal-body #name_of_item').val(data[1]);
    $('#receive').find('.modal-body #pr_id').val(formatMoney(data[3]));
    $('#receive').find('.modal-body #product-id').val(data[0]);
    $('#receive').find('.modal-body #order-item-id').val(data[7]);
    $('#receive').find('.modal-body #id_of_supplier').val(data[6]);
    $('#receive').find('.modal-body #batch_number').val('');
    $('#receive').find('.modal-body #expire_date_1').val('');


    var supplier_ids = data[7];
    var item_id = data[0];
    var e = document.getElementById("price_cat");
    var price_cat = e.options[e.selectedIndex].value;

    document.getElementById('save_btn').style.display = 'block';

    $('#receive').on('change', '#rec_qty', function() {
        var quantity_ = document.getElementById('rec_qty').value;
        var quantity = parseFloat(quantity_.replace(/\,/g, ''), 10);
        // if (quantity > data[2]) {
        //     document.getElementById('save_btn').disabled = 'true';
        //     document.getElementById('qty_error').style.display = 'block';
        //     $('#receive').find('.modal-body #qty_error').text('The maximum quantity is ' + numberWithCommas(data[2]));
        // } else {
        //     document.getElementById('qty_error').style.display = 'none';
        //     $('#save_btn').prop('disabled', false);
        // }
    });

});

function orderamountCheck() {

    var unit_price = document.getElementById('pr_id').value;
    var sell_price = document.getElementById('sell_price_i').value;
    unit_price_parse = (parseFloat(unit_price.replace(/\,/g, ''), 10));
    sell_price_parse = (parseFloat(sell_price.replace(/\,/g, ''), 10));

    document.getElementById('sell_price_i').value = formatMoney(parseFloat(sell_price.replace(/\,/g, ''), 10));
    document.getElementById('pr_id').value = formatMoney(parseFloat(unit_price.replace(/\,/g, ''), 10));


    if (Number(sell_price_parse) < Number(unit_price_parse)) {

        $('#save_btn').prop('disabled', true);
        $('div.amount_error').text('Cannot be less than Buy Price');
    } else if (sell_price_parse == unit_price_parse) {
        $('#save_btn').prop('disabled', true);
        $('div.amount_error').text('Cannot be equal to Buy Price');
    } else {

        $('#save_btn').prop('disabled', false);
        $('div.amount_error').text('');

    }
}

function priceByCategory() {

    if (product_ids !== 0) {
        var price_category = document.getElementById("price_category").value;
        getPrice(product_ids, price_category);

    } else {
        return false;
    }

}

$('#invoice_id').prop('disabled', true);

function filterInvoiceBySupplier() {
    var supplier = document.getElementById('supplier_ids');
    var supplier_id = supplier.options[supplier.selectedIndex].value;

    /*ajax filter invoice by supplier*/
    $.ajax({
        url: config.routes.filterBySupplier,
        type: "get",
        dataType: "json",
        data: {
            supplier_id: supplier_id
        },
        success: function(data) {
            $('#invoice_id').prop('disabled', false);
            $("#invoice_id option").remove();
            $('#invoice_id').append($('<option>', {
                value: '',
                text: 'Select Invoice...',
                disabled: true,
                selected: true
            }));
            $.each(data, function(id, detail) {
                var datas = [detail.id];

                $('#invoice_id').append($('<option>', { value: datas, text: detail.invoice_no }));
            });
        },
        complete: function() {
            // $('#loading').hide();
        }
    });

}
function filterReceivingInvoiceBySupplier() {
    var supplier = document.getElementById('good_receiving_supplier_ids');
    var supplier_id = supplier.options[supplier.selectedIndex].value;

    /*ajax filter invoice by supplier*/
    $.ajax({
        url: config.routes.filterBySupplier,
        type: "get",
        dataType: "json",
        data: {
            supplier_id: supplier_id
        },
        success: function(data) {
            $('#invoice_id').prop('disabled', false);
            $("#invoice_id option").remove();
            $('#invoice_id').append($('<option>', {
                value: '',
                text: 'Select Invoice...',
                disabled: true,
                selected: true
            }));
            $.each(data, function(id, detail) {
                var datas = [detail.id];

                $('#invoice_id').append($('<option>', { value: datas, text: detail.invoice_no }));
            });
        },
        complete: function() {
            // $('#loading').hide();
        }
    });

}

$('#myFormId').on('submit', function(e) {
    e.preventDefault();

    var cart_data = document.getElementById("received_cart").value;

    if (cart_data === '') {
        notify('Item receive list is empty', 'top', 'right', 'warning');
        return false;
    }

    var item_cart = JSON.parse(cart_data);

    if (item_cart.length === 0) {
        notify('Item receive list is empty', 'top', 'right', 'warning');
        return false;
    }

    saveInvoiceForm();

});

$('#invoiceFormId').on('submit', function(e) {
    e.preventDefault();

    var cart_data = document.getElementById("invoice_received_cart").value;

    if (cart_data === '') {
        notify('Item receive list is empty', 'top', 'right', 'warning');
        return false;
    }

    var item_cart = JSON.parse(cart_data);

    if (item_cart.length === 0) {
        notify('Item receive list is empty', 'top', 'right', 'warning');
        return false;
    }
    // $('#invoicesave_id').attr('disabled', true);

    invoicesaveInvoiceForm();

});

function saveInvoiceForm() {
    var form = $('#myFormId').serialize();

    $.ajax({
        url: config.routes.itemFormSave,
        type: "get",
        dataType: "json",
        cache: "false",
        data: form,
        success: function(data) {
            if (data[0].message === 'success') {
                notify('Item received successfully', 'top', 'right', 'success');
                document.getElementById('buy_price').value = '0';
                document.getElementById('sell_price_id').value = '0';
                document.getElementById('expire_date_21').value = '';
                deselect();
            } else {
                notify('Item name exists', 'top', 'right', 'danger');
                document.getElementById('buy_price').value = '0';
                document.getElementById('sell_price_id').value = '0';
                document.getElementById('expire_date_21').value = '';
                deselect();
            }
        }
    });
}

function invoicesaveInvoiceForm() {
    var form = $('#invoiceFormId').serialize();

    $.ajax({
        url: config.routes.invoiceFormSave,
        type: "get",
        dataType: "json",
        cache: "false",
        data: form,
        success: function(data) {
            if (data[0].message === 'success') {
                notify('Item received successfully', 'top', 'right', 'success');
                invoicecart_table.clear();
                invoicecart_table.draw();
                invoice_cart = [];
                invoice_cart_receiveds = [];
                // $('#good_receiving_supplier_ids').val('').change();
                // document.getElementById('store_id').value = '';
                document.getElementById('goodreceving_invoice_id').value = '';
                document.getElementById("invoicing_batch_n").value = '';
                document.getElementById('invoiceselected-product').value = ''
                console.log($('#invoicing_purchase_date').value);
                document.getElementById("total_selling_price").value = '0.00';
                document.getElementById("total_buying_price").value = '0.00';
                document.getElementById("sub_total").value = '0.00';
                $("#price_category").prop("disabled", false);
                deselect();
            } else {
                notify('Invoice exists', 'top', 'right', 'danger');
                invoicecart_table.clear();
                invoicecart_table.draw();
                invoice_cart = [];
                invoice_cart_receiveds = [];
                document.getElementById('invoiceselected-product').value = '';
                deselect();
            }
        }
    });
}

$('#order_receive').on('submit', function(e) {
    e.preventDefault();

    var unit_price = document.getElementById('pr_id').value;
    var sell_price = document.getElementById('sell_price_i').value;
    var unit_price_parse = (parseFloat(unit_price.replace(/\,/g, ''), 10));
    var sell_price_parse = (parseFloat(sell_price.replace(/\,/g, ''), 10));

    if (sell_price_parse === 0 || unit_price_parse === 0) {
        notify('Item cannot have 0 price', 'top', 'right', 'warning');
        return false;
    }

    if (b > 0) {
        let date = document.getElementById('expire_date_1').value;
        if (date === '') {
            notify('Expire Date is required', 'top', 'right', 'warning');
            return false;
        }
    }

    saveOrderReceiveForm();


});

function saveOrderReceiveForm() {
    var form = $('#order_receive').serialize();

    $.ajax({
        url: config.routes.orderFormSave,
        type: "get",
        dataType: "json",
        cache: "false",
        data: form,
        success: function(data) {
            if (data[0].message === 'success') {
                notify('Order received successfully', 'top', 'right', 'success');
                items_table.row(remove_row_index).remove().draw();
                $('#receive').modal('hide');
            }
        }
    });
}

function toCommas(number) {
    document.getElementById('rec_qty').value = numberWithCommas(number);
}

function numberWithCommas(digit) {
    return String(parseFloat(digit)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
