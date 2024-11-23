const acc_type = $("#acc_type").select2({
    theme: selectProperties.theme,
    width: selectProperties.width,
    placeholder: selectProperties.placeholder,
    ajax: {
        url: "./queries/to_get_accounts_types.php",
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