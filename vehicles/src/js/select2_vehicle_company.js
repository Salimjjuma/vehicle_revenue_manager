// select2 function to fill the vehicle company field in the modal
const vehicle_company = $("#vehicle_company").select2({
    theme: selectProperties.theme,
    width: selectProperties.width,
    placeholder: selectProperties.placeholder,
    ajax: {
        url: "./queries/get_all_vehicle_company.php",
        type: "POST",
        dataType: "json",
        delay: 250,
        data: function (params) {
            return {
                searchTerm: params.term,
            };
        },
        processResults: function (response) {
            return {
                results: response,
            };
        },
        cache: true,
    }
})