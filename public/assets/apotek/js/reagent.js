var cart = [];//for data displayed.
var default_cart = [];//for default values.
var details = [];
var sale_items = [];
var edit_btn_set = 0;
var cart_table = $('#cart_table').DataTable({
    searching: false,
    bPaginate: false,
    bInfo: false,
    ordering: false,
    data: cart,
    columns: [
        {title: "Reagent Name"},
        {title: "Quantity"},
        {title: "Unit Cost"},
        {title: "reagent_id"},
        {title: "Amount"},
        {
            title: "Action",
            defaultContent: "<button class='btn btn-primary btn-sm btn-rounded' type='button' id='edit_btn'>Edit</button><button class='btn btn-danger btn-sm btn-rounded' type='button' value='Delete' id='delete_btn'> Delete</button>"
        }

    ]

});
cart_table.columns([3]).visible(false);//this columns are just for manipulations

$("#reagents").on('change', function () {
    valueCollection();
});

$('#cart_table tbody').on('click', '#edit_btn', function () {
    var quantity;
    var price;
    if (edit_btn_set === 0) {
        var row_data = cart_table.row($(this).parents('tr')).data();

        var index = cart_table.row($(this).parents('tr')).index();
        quantity = row_data[1].toString().replace(',', '');
        price = row_data[2];
        row_data[1] = "<input type='text' min='1' style='width:70%' class='form-control' id='edit_quantity' required  onkeypress='return isNumberKey(event,this)'>";
        row_data[2] = "<input style='width:130%; margin-left: -20%' type='text' class='form-control' id='edit_price' required  onkeypress='return isNumberKey(event,this)'>";
        cart[index] = row_data;
        cart_table.clear();
        cart_table.rows.add(cart);
        cart_table.draw();

        var quantity_ = quantity.split('<');

        document.getElementById("edit_quantity").value = quantity_[0];
        document.getElementById("edit_price").value = price;

        edit_btn_set = 1;

    } else {
        $('#edit_quantity').change();
        $('#edit_price').change();

    }
});

$('#cart_table tbody').on('click', '#show_btn', function () {
    let row_data = cart_table.row($(this).parents('tr')).data();
    let index = cart_table.row($(this).parents('tr')).index();

    if (row_data[7] !== undefined) {
        //retrieve
        $.ajax({
            url: config.routes.showPrescription,
            type: "get",
            dataType: "json",
            data: {
                detail_id: row_data[7]
            },
            success: function (data) {
                document.getElementById('heading').innerHTML = data.product.name;
                document.getElementById('strength_show').value = data.strength;
                document.getElementById('dosage_show').value = data.dosage;
                document.getElementById('frequency_show').value = data.frequency;
                document.getElementById('duration_show').value = data.duration;
                document.getElementById('quantity_show').value = numberWithCommas(data.quantity);

                $('#show').modal('show');
            }
        });
    }

});

$('#cart_table tbody').on('change', '#edit_quantity', function () {
    edit_btn_set = 0;
    var row_data = cart_table.row($(this).parents('tr')).data();
    var index = cart_table.row($(this).parents('tr')).index();

    if (document.getElementById("edit_quantity").value === '') {
        row_data[1] = numberWithCommas(1);
    } else {
        row_data[1] = numberWithCommas(document.getElementById("edit_quantity").value);
    }

    row_data[2] = formatMoney(parseFloat(document.getElementById("edit_price").value.replace(/\,/g, ''), 10));


    // row_data[1] = Number((document.getElementById("edit_quantity").value));
    if (Number(parseFloat(row_data[1].replace(/\,/g, ''), 10)) < 1) {
        row_data[1] = 1
    }

    row_data[2] = formatMoney(parseFloat(document.getElementById("edit_price").value.replace(/\,/g, ''), 10));
    row_data[4] = formatMoney(parseFloat(document.getElementById("edit_price").value.replace(/\,/g, ''), 10) * row_data[1].toString().replace(',', ''));

    cart[index] = row_data;
    discount();
});

$('#cart_table tbody').on('change', '#edit_price', function () {
    edit_btn_set = 0;
    var row_data = cart_table.row($(this).parents('tr')).data();
    var index = cart_table.row($(this).parents('tr')).index();

    row_data[1] = numberWithCommas(document.getElementById("edit_quantity").value);
    row_data[2] = formatMoney(parseFloat(document.getElementById("edit_price").value.replace(/\,/g, ''), 10));


    // console.log(row_data)
    // return false;
    if (Number(parseFloat(row_data[1].replace(/\,/g, ''), 10)) < 1) {
        row_data[1] = 1
    }

    row_data[2] = formatMoney(parseFloat(document.getElementById("edit_price").value.replace(/\,/g, ''), 10));
    row_data[4] = formatMoney(parseFloat(parseFloat(document.getElementById("edit_price").value.replace(/\,/g, ''), 10) * row_data[1].toString().replace(',', '')));

    cart[index] = row_data;
    discount();
});


$('#cart_table tbody').on('click', '#delete_btn', function () {
    edit_btn_set = 0;
    var index = cart_table.row($(this).parents('tr')).index();
    var price = parseFloat(cart[index][2].replace(/\,/g, ''), 10);
    var unit_total = parseFloat(cart[index][4].replace(/\,/g, ''), 10);
    cart.splice(index, 1);
    default_cart.splice(index, 1);
    discount();
});

$('#deselect-all').on('click', function () {
    edit_btn_set = 0;
    var cart_data = document.getElementById("order_cart").value;
    if (!(cart_data === '' || cart_data === 'undefined')) {
        var r = confirm('Cancel reagent receiving?');
        if (r === true) {
            /*continue*/
            deselect();
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
    } catch (e) {
    }
}

function discount() {
    var total = 0;
    var order_cart = [];

    var stringified_cart;
    if (cart[0]) {


        var reduced__obj_cart = {}, incremental_cart;

        for (var i = 0, c; c = cart[i]; ++i) {
            if (undefined === reduced__obj_cart[c[0]]) {
                reduced__obj_cart[c[0]] = c;
                reduced__obj_cart[c[0]][4] =
                    formatMoney(Number(reduced__obj_cart[c[0]][1].toString().replace(',', '')) * parseFloat(c[2].replace(/\,/g, ''), 10));
            } else {
                reduced__obj_cart[c[0]][1] = Number(reduced__obj_cart[c[0]][1].toString().replace(',', '')) + Number(c[1]);
                reduced__obj_cart[c[0]][4] =
                    formatMoney(Number(reduced__obj_cart[c[0]][1].toString().replace(',', '')) * parseFloat(reduced__obj_cart[c[0]][2].replace(/\,/g, ''), 10));
                reduced__obj_cart[c[0]][1] = numberWithCommas(reduced__obj_cart[c[0]][1]);
            }
        }

        incremental_cart = Object.keys(reduced__obj_cart).map(function (val) {
            return reduced__obj_cart[val]
        });

        cart = incremental_cart;

        cart.forEach(function (item, index, arr) {
            var reagent_received = {};
            reagent_received.quantity = item[1];
            reagent_received.unit_price = item[2];
            reagent_received.reagent_id = item[3];
            reagent_received.sub_totals = item[4];
            total += parseFloat(item[4].replace(/\,/g, ''), 10);
            order_cart.push(reagent_received);
        });
        stringified_cart = JSON.stringify(order_cart);
    }
    document.getElementById("order_cart").value = stringified_cart;
    document.getElementById("total").value = formatMoney(total);

    cart_table.clear();
    cart_table.rows.add(cart);
    cart_table.draw();
}


function valueCollection() {
    $('#edit_quantity').change();
    var item = [];
    var cart_data = [];
    let reagent = document.getElementById("reagents").value;

    document.getElementById("reagents").value = "";
    if (reagent) {
        var selected_fields = reagent.split('#@');

        var reagent_name = selected_fields[0];
        var unit_cost = Number(selected_fields[1]);
        var reagent_id = Number(selected_fields[2]);

        item.push(reagent_name);
        item.push("1");
        item.push(formatMoney(unit_cost));
        item.push(reagent_id);
        item.push(formatMoney(0));
        cart_data.push(formatMoney(unit_cost));
        cart.push(item);
        default_cart.push(cart_data);
        discount();

    }

}


function deselect() {
    document.getElementById('total').value = 0.0;
    cart = [];
    order_cart = [];
    default_cart = [];
    discount();
}


$('#reagents').select2({
    language: {
        noResults: function () {
            var search_input = $("#reagents").data('select2').$dropdown.find("input").val();
            var price_category = document.getElementById('price_category');
            var price_category_id = price_category.options[price_category.selectedIndex].value;

            /*make ajax call for more*/
            $.ajax({
                url: config.routes.filterProductByWord,
                type: "get",
                dataType: "json",
                data: {
                    word: search_input,
                    price_category_id: price_category_id
                },
                success: function (result) {
                    $("#reagents option").remove();
                    $.each(result, function (detail, name) {
                        $('#reagents').append($('<option>', {value: detail, text: name}));
                    });
                }
            });
        }
    }
});


$('#sales_form').on('submit', function (e) {

    var cart = document.getElementById("order_cart").value;

    if (cart === '' || cart === 'undefined') {
        notify('Reagent list empty', 'top', 'right', 'warning');
        return false;
    }

    var check_cart_to_array;
    if (cart === 'undefined') {
        check_cart_to_array = [];
    } else {
        check_cart_to_array = JSON.parse(cart);
    }

    //check_cart the cart array if quantity tran is missing
    var unit_price = "unit_price";
    for (var key in check_cart_to_array) {
        if (check_cart_to_array[key].hasOwnProperty(unit_price)) {
            //present
            // if (Number(check_cart_to_array[key][unit_price]) === Number(0)) {
            //     notify('Unit cost cannot be 0', 'top', 'right', 'warning');
            //     return false;
            // }
        }
    }

});

function isNumberKey(evt, obj) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var value = obj.value;
    var dotcontains = value.indexOf(".") !== -1;
    if (dotcontains)
        if (charCode === 46) return false;
    if (charCode === 46) return true;
    return !(charCode > 31 && (charCode < 48 || charCode > 57));

}

function numberWithCommas(digit) {
    return String(parseFloat(digit)).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
