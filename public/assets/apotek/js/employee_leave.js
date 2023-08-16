function daysCalculation(edit) {
    if (edit !== 1) {
        let from = new Date(document.getElementById('from_date').value);
        let to = new Date(document.getElementById('to_date').value);
        let difference_time = to.getTime() - from.getTime();
        let difference_day = difference_time / (1000 * 3600 * 24);
        if (difference_day < 0) {
            notify('To Date Must Be Greater Than From Date', 'top', 'right', 'warning');
            document.getElementById('no_of_days').value = 0;
            return false;
        }
        if (!isNaN(difference_day)) {
            document.getElementById('no_of_days').value = difference_day;
        }

        /*calculate remaining days*/
        let leave_type_duration = document.getElementById('leave_type_duration').value;
        let requested_days = document.getElementById('no_of_days').value;
        let used_duration = document.getElementById('total_requested_duration').value;

        let result = Number(leave_type_duration) - (Number(difference_day) + Number(used_duration));

        if (!(isNaN(result))) {
            document.getElementById('remaining_days').value = result;
        }

        if (result < 0) {
            document.getElementById('remaining_days').value = 0;
            let correct_days = Number(requested_days) + Number(result);
            document.getElementById('no_of_days').value = correct_days;
            /*set to date*/
            let difference_time = correct_days * (1000 * 3600 * 24);
            let from = new Date(document.getElementById('from_date').value);
            let to_time = from.getTime() + difference_time;
            let valid_date = new Date(to_time);
            document.getElementById('to_date').value = valid_date.toLocaleDateString();

            notify('Maximum remaining days are ' +
                (Number(leave_type_duration) - Number(used_duration)), 'top', 'right', 'warning');
        } else {
            document.getElementById('remaining_days').value = result;
        }

    } else {
        let from_edit = new Date(document.getElementById('from_date_edit').value);
        let to_edit = new Date(document.getElementById('to_date_edit').value);
        let difference_time_edit = to_edit.getTime() - from_edit.getTime();
        let difference_day_edit = difference_time_edit / (1000 * 3600 * 24);
        if (difference_day_edit < 0) {
            notify('To Date Must Be Greater Than From Date', 'top', 'right', 'warning');
            document.getElementById('no_of_days_edit').value = 0;
            return false;
        }
        if (!isNaN(difference_day_edit)) {
            document.getElementById('no_of_days_edit').value = difference_day_edit;
        }

        /*calculate remaining days*/
        let leave_type_duration_edit = document.getElementById('leave_type_duration_edit').value;
        let requested_duration_total = document.getElementById('total_requested_duration_edit').value;
        let initial_duration = document.getElementById('initial_duration').value;

        let requested_days_edit = document.getElementById('no_of_days_edit').value;
        let incremental_days = (Number(requested_days_edit) - Number(initial_duration)) + Number(requested_duration_total);
        let result_edit = Number(leave_type_duration_edit) - Number(incremental_days);
        if (result_edit < 0) {
            document.getElementById('remaining_days_edit').value = 0;
            let correct_days = Number(requested_days_edit) + Number(result_edit);
            document.getElementById('no_of_days_edit').value = correct_days;
            /*set to date*/
            difference_time_edit = correct_days * (1000 * 3600 * 24);
            let from_edit = new Date(document.getElementById('from_date_edit').value);
            let to_time = from_edit.getTime() + difference_time_edit;
            let valid_date = new Date(to_time);
            document.getElementById('to_date_edit').value = valid_date.toLocaleDateString();

            notify('Maximum remaining days are ' + requested_duration_total, 'top', 'right', 'warning');
        } else {
            document.getElementById('remaining_days_edit').value = result_edit;
        }

    }

}

function filterLeave(leave_type_id) {
    let employee_id;

    let employee = document.getElementById("employee_id");
    let employee_id_create = employee.options[employee.selectedIndex].value;

    let employee_edit = document.getElementById("employee_id_edit");
    let employee_id_edit = employee_edit.options[employee_edit.selectedIndex].value;

    if (employee_id_create === '') {
        employee_id = employee_id_edit;
    }

    if (employee_id_edit === '') {
        employee_id = employee_id_create;
    }


    $.ajax({
        url: config.routes.filterLeaveDuration,
        type: "get",
        dataType: "json",
        data: {
            leave_type_id: leave_type_id,
            employee_id: employee_id
        }, success: function (data) {
            if (edit_btn_click === 1) {
                document.getElementById('leave_type_duration_edit').value = data[0];
                document.getElementById('total_requested_duration_edit').value = data[1];
                daysCalculation(1);
            } else {
                document.getElementById('leave_type_duration').value = data[0];
                document.getElementById('total_requested_duration').value = data[1];
                daysCalculation();
            }
        }
    });
}

$('#new_leave').on('click', function () {
    edit_btn_click = 0;
    document.getElementById("employee_id_edit").value = '';
});

function filterEmployeeLeave(employee_id) {
    $.ajax({
        url: config.routes.filterEmployeeLeave,
        type: "get",
        dataType: "json",
        data: {
            employee_id: employee_id
        }, success: function (data) {
            $("#leave_type_id option").remove();
            $('#leave_type_id').append($('<option>', {
                value: '',
                text: 'Select Type...',
                selected: true,
                disabled: true
            }));
            $.each(data, function (id, detail) {

                $('#leave_type_id').append($('<option>', {value: detail.leave_id, text: detail.leave_type}));
            });
        }
    });
}

function showLeaveDetail(employee_id, leave_type_id) {
    $.ajax({
        url: config.routes.showLeaveDetail,
        type: "get",
        dataType: "json",
        data: {
            employee_id: employee_id,
            leave_type_id: leave_type_id
        }, success: function (data) {
            document.getElementById('remaining_days_show').value = data;
        }
    });
}

function showLeaveDetailEntitlement(employee_id, leave_id) {
    $.ajax({
        url: config.routes.showLeaveDetailEntitlement,
        type: "get",
        dataType: "json",
        data: {
            employee_id: employee_id,
            leave_type_id: leave_id
        }, success: function (data) {
            let remain = Number(data[0].total_duration) - data[0].total_approved;
            if (Number(data[0].total_duration) >= Number(2)) {
                document.getElementById('total_duration_show').value = data[0].total_duration + ' days';
            } else {
                document.getElementById('total_duration_show').value = data[0].total_duration + ' day';
            }
            if (Number(data[0].total_request) >= Number(2)) {
                document.getElementById('requested_show').value = data[0].total_request + ' days';
            } else {
                document.getElementById('requested_show').value = data[0].total_request + ' day';
            }
            if (Number(data[0].total_pending) >= Number(2)) {
                document.getElementById('pending_approval_show').value = data[0].total_pending + ' days';
            } else {
                document.getElementById('pending_approval_show').value = data[0].total_pending + ' day';
            }
            if (Number(data[0].total_approved) >= Number(2)) {
                document.getElementById('approved_show').value = data[0].total_approved + ' days';
            } else {
                document.getElementById('approved_show').value = data[0].total_approved + ' day';
            }
            if (Number(data[0].total_rejected) >= Number(2)) {
                document.getElementById('rejected_show').value = data[0].total_rejected + ' days';
            } else {
                document.getElementById('rejected_show').value = data[0].total_rejected + ' day';
            }
            if (Number(remain) >= Number(2)) {
                document.getElementById('remaining_show').value = remain + ' days';
            } else {
                document.getElementById('remaining_show').value = remain + ' day';
            }

        }
    });
}
