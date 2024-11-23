const company_table = $("#company_table").DataTable({
    ajax: {
        url: "./queries/to_get_all_the_brands",
        type: "GET",
        dataSrc: "",
    },
    columnDefs: [{
            targets: 0,
            data: "created_at",
        },
        {
            targets: 1,
            data: "company_name",
        },
        {
            targets: 2,
            data: "isActive",
            render: ((data) => {
                if (data == 1) {
                    return `<span class="badge rounded-pill text-bg-success">Active</span>`;
                } else {
                    return `<span class="badge rounded-pill text-bg-danger">InActive</span>`;
                }
            })
        }
    ]
});