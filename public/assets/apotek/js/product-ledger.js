var table_ledger_filter = $('#fixed-header-ledger').DataTable({
    searching: true,
    bPaginate: true,
    bInfo: true,
    'columns': [
        {'data': 'date'},
        {'data': 'method'},
        {'data': 'quantity'},
        {'data': 'balance'},
        {'data': 'movement'},
        {'data': 'created_by'}
    ]
});

// date
$('#d_auto_7').datepicker({
    todayHighlight: true,
    format: 'yyyy-mm-dd',
    changeYear: true,

}).on('change', function () {
    //check if product is selected
    var product = document.getElementById("select_id");
    var product_id = product.options[product.selectedIndex].value;

    if (Number(product_id) !== 0) {
        productLedgerFilter();
    }

    $('.datepicker').hide();
}).attr('readonly', 'readonly');

// product ledger filter ajax call
function productLedgerFilter() {

    var product = document.getElementById("select_id");
    var product_id = product.options[product.selectedIndex].value;

    var date = document.getElementById("d_auto_7").value;


    var ajaxurl = config.routes.ledgerShow;
    $('#loading').show();
    $.ajax({
        url: ajaxurl,
        type: "get",
        dataType: "json",
        data: {
            date: date,
            product_id: product_id
        },
        success: function (data) {
            bindData(data);
        },
        complete: function () {
            $('#loading').hide();
        }
    });

}

function bindData(data) {
    table_ledger_filter.clear();
    table_ledger_filter.rows.add(data);
    table_ledger_filter.draw();
}
