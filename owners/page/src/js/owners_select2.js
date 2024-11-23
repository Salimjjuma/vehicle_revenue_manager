$("#vehicle").select2({
    theme: selectProperties.theme,
    width: selectProperties.width,
    placeholder: selectProperties.placeholder,
    ajax: {
        url: "./queries/to_get_all_the_vehicles_in_the_db",
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
});