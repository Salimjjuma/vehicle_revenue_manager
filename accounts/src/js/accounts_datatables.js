const accounts_table = $("#accounts_table").DataTable({
    ajax: ({
        url: "./queries/get_all_the_accounts_in_th_db",
        type: "GET",
        dataSrc: "",
    }),
    columnDefs: [{
            targets: 0,
            data: "creation_date"
        },
        {
            targets: 1,
            data: "acc_name"
        },
        {
            targets: 2,
            data: "acc_description"
        },
        {
            targets: 3,
            data: "status",
            render: ((data) => {
                if (data == 1) {
                    return `<span class="badge rounded-pill text-bg-success">Active</span>`;
                } else {
                    return `<span class="badge rounded-pill text-bg-success">InActive</span>`;
                }
            })
        }

    ]

});