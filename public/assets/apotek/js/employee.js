function filterDesignation(department_id, edit) {
    let department;
    let department_edit_id;
    try {

        department = document.getElementById('department_edit_id');
        department_edit_id = department.options[department.selectedIndex].value;
    } catch (e) {

    }

    if (edit === 1) {
        department_id = department_edit_id;
        $("#designation_edit_ids option").remove();
    } else {
        $("#designation_id option").remove();
    }

    $.ajax({
        url: config.routes.filterDesignations,
        type: "get",
        dataType: "json",
        data: {
            department_id: department_id
        }, success: function (data) {
            if (edit === 1) {
                $('#designation_edit_ids').append($('<option>', {
                    value: '',
                    text: 'Select Designation...',
                    selected: true,
                    disabled: true
                }));
                $.each(data, function (id, detail) {
                    $('#designation_edit_ids').append($('<option>', {value: detail.id, text: detail.name}));
                });
            } else {
                $('#designation_id').append($('<option>', {
                    value: '',
                    text: 'Select Designation...',
                    selected: true,
                    disabled: true
                }));
                $.each(data, function (id, detail) {
                    $('#designation_id').append($('<option>', {value: detail.id, text: detail.name}));
                });
            }
        }
    });

}

function filterDepartmentEdit(organisation_id) {
    if (edit_btn_click === 1) {
        edit_btn_click = 0;
    } else {
        filterDepartment(organisation_id, 1);
    }
}

function filterDesignationEdit(department_id) {
    if (edit_btn_click_1 === 1) {
        edit_btn_click_1 = 0;
    } else {
        filterDesignation(department_id, 1);
    }

}
