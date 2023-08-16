$('#merger_patient').select2({

    ajax: {
        url: config.routes.getPatients,
        type: "get",
        delay: 250,
        dataType: "json",
        data: function (params) {
            let show_all_patient = 1;
            var query = {
                search: params.term,
                all: show_all_patient ? 1 : 0,
            }
            return query;
        },
        processResults: function (data, page) {
            data = data.reduce(function (array, patient) {
                array.push({
                    id: patient.id,
                    text: patient.patient_name
                });
                return array;
            }, []);
            return {
                results: data
            }
        }
    },
});


$('#merged_patient').select2({

    ajax: {
        url: config.routes.getPatients,
        type: "get",
        delay: 250,
        dataType: "json",
        data: function (params) {
            let show_all_patient = 1;
            var query = {
                search: params.term,
                all: show_all_patient ? 1 : 0,
            }
            return query;
        },
        processResults: function (data, page) {
            data = data.reduce(function (array, patient) {
                array.push({
                    id: patient.id,
                    text: patient.patient_name
                });
                return array;
            }, []);
            return {
                results: data
            }
        }
    },
});


function onselectMergerPatient() {
    let patient_id = document.getElementById("merger_patient").value;
    console.log(patient_id)



    if (patient_id !== document.getElementById("merged_patient_id").value) {

        $.ajax({
            url: config.routes.getPatient,
            data: {
                "_token": config.token,
                "id": patient_id
            },
            type: 'get',
            delay: 50,
            dataType: 'json',
            success: function (result) {

                $('#merger_patient').empty();
                $("#merger_patient").append('<option  disabled selected > Select Patient </option>');
                if (result) {
                    document.querySelector('.patient_header').innerHTML = ''
                    $.each(result, function (array, detail) {
                        document.querySelector('.patient_header').innerHTML += '<span class="pcoded-micon d-inline text-success"><i class="fas fa-user mr-2"></i>Merger Patient Details</span>'
                        document.querySelector('.merger_name_header').textContent = 'Name:';
                        document.querySelector('.merger_name').textContent = (detail.patient_name) ? detail.patient_name : '-';
                        document.querySelector('.merger_sex_header').textContent = 'Gender:';
                        document.querySelector('.merger_sex').textContent = (detail.sex) ? detail.sex : '-';
                        document.querySelector('.merger_martital_status_header').textContent = 'Martital Status:';
                        document.querySelector('.merger_martital_status').textContent = (detail.martital_status) ? detail.martital_status : '-';
                        document.querySelector('.merger_age_header').textContent = 'Age:';
                        document.querySelector('.merger_age').textContent = (detail.age) ? detail.age : '-';
                        document.querySelector('.merger_phone_number_header').textContent = 'Phone Number:';
                        document.querySelector('.merger_phone_number').textContent = (detail.phone_1) ? detail.phone_1 : '-';
                        document.querySelector('.merger_address_header').textContent = 'Address:';
                        document.querySelector('.merger_address').textContent =(detail.address) ? detail.address : '-';

                    });
                    document.getElementById("merger_patient_id").value = patient_id;
                }
            },
        });
        
    } else {
        notify('Patient Already Selected!', 'top', 'right', 'warning');
    }



    
}


function onselectMergedPatient() {
    let patient_id = document.getElementById("merged_patient").value;

    if (patient_id !== document.getElementById("merger_patient_id").value) {

        $.ajax({
            url: config.routes.getPatient,
            data: {
                "_token": config.token,
                "id": patient_id
            },
            type: 'get',
            delay: 50,
            dataType: 'json',
            success: function (result) {

                $('#merged_patient').empty();
                $("#merged_patient").append('<option  disabled selected > Select Patient to be Merged </option>');
                if (result) {
                    document.querySelector('.merged_patient_header').innerHTML = ''
                    $.each(result, function (array, detail) {
                        document.querySelector('.merged_patient_header').innerHTML += '<span class="pcoded-micon d-inline text-warning"><i class="fas fa-user mr-2"></i>Merged Patient Details</span>'
                        document.querySelector('.merged_name_header').textContent = 'Name:';
                        document.querySelector('.merged_name').textContent = (detail.patient_name) ? detail.patient_name : '-';
                        document.querySelector('.merged_sex_header').textContent = 'Gender:';
                        document.querySelector('.merged_sex').textContent = (detail.sex) ? detail.sex : '-';
                        document.querySelector('.merged_martital_status_header').textContent = 'Martital Status:';
                        document.querySelector('.merged_martital_status').textContent = (detail.martital_status) ? detail.martital_status : '-';
                        document.querySelector('.merged_age_header').textContent = 'Age:';
                        document.querySelector('.merged_age').textContent = (detail.age) ? detail.age : '-';
                        document.querySelector('.merged_phone_number_header').textContent = 'Phone Number:';
                        document.querySelector('.merged_phone_number').textContent = (detail.phone_1) ? detail.phone_1 : '-';
                        document.querySelector('.merged_address_header').textContent = 'Address:';
                        document.querySelector('.merged_address').textContent = (detail.address) ? detail.address : '-';

                    });
                    document.querySelector('.arrow_button').style.display = "block";
                    document.getElementById("merged_patient_id").value = patient_id;
                    console.log('Merger ' + document.getElementById("merger_patient_id").value)
                    console.log('Merged ' + document.getElementById("merged_patient_id").value)
                } else {
                    document.querySelector('.arrow_button').style.display = "none";
                }
            },
        });
    } else {
        notify('Patient Already Selected!', 'top', 'right', 'warning');
    }

}



function mergeAction() {
    let merger_id = document.getElementById("merger_patient_id").value;
    let merged_id = document.getElementById("merged_patient_id").value;

    if (merger_id && merged_id) {

        $.ajax({
            url: config.routes.mergeAction,
            data: {
                "_token": config.token,
                "merger_id": merger_id,
                "merged_id": merged_id,
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data[0].message === 'success') {
                    notify('Patient Merged Successfully!', 'top', 'right', 'success');
                    $('#confrimMerge').modal('hide');
                    resetForms();
                    document.querySelector('.arrow_button').style.display = "none";
                    
                } else if (data[0].message === 'merger exist') {
                    
                    notify('Something went wrong, try again!', 'top', 'right', 'warning');
                    $('#confrimMerge').modal('hide');
                    resetForms();
                    document.querySelector('.arrow_button').style.display = "none";
                }
            },
        });
    }
}

function resetForms() {

    document.querySelector('.patient_header').innerHTML = ''
    document.querySelector('.merger_name_header').textContent = '';
    document.querySelector('.merger_name').textContent = '';
    document.querySelector('.merger_sex_header').textContent ='';
    document.querySelector('.merger_sex').textContent ='';
    document.querySelector('.merger_martital_status_header').textContent ='';
    document.querySelector('.merger_martital_status').textContent ='';
    document.querySelector('.merger_age_header').textContent ='';
    document.querySelector('.merger_age').textContent ='';
    document.querySelector('.merger_phone_number_header').textContent ='';
    document.querySelector('.merger_phone_number').textContent ='';
    document.querySelector('.merger_address_header').textContent ='';
    document.querySelector('.merger_address').textContent = '';
    document.querySelector('.merged_patient_header').innerHTML = '';
    document.querySelector('.merged_name_header').textContent = '';
    document.querySelector('.merged_name').textContent = '';
    document.querySelector('.merged_sex_header').textContent = '';
    document.querySelector('.merged_sex').textContent = '';
    document.querySelector('.merged_martital_status_header').textContent = '';
    document.querySelector('.merged_martital_status').textContent = '';
    document.querySelector('.merged_age_header').textContent = '';
    document.querySelector('.merged_age').textContent = '';
    document.querySelector('.merged_phone_number_header').textContent = '';
    document.querySelector('.merged_phone_number').textContent = '';
    document.querySelector('.merged_address_header').textContent = '';
    document.querySelector('.merged_address').textContent = '';
}