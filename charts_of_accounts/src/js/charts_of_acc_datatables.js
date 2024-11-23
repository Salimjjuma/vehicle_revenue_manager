const charts_of_accounts_table = $("#charts_of_accounts_table").DataTable({
    ajax: {
        url: "./queries/to_get_all_the_charts_in_the_db.php",
        type: "GET",
        dataSrc: "",
    },
    columnDefs: [{
            targets: 0,
            data: "creation_date",
        }, {
            targets: 1,
            data: {
                "revenue_name": "revenue_name",
                "charts_of_accounts_id": "charts_of_accounts_id"
            },
            render: ((data) => {
                return `<a href="./page/?cid=${data.charts_of_accounts_id}">${data.revenue_name}</a>`
            })
        }, {
            targets: 2,
            data: "revenue_description",
        },
        {
            targets: 3,
            data: "acc_name"
        },
        {
            targets: 4,
            data: "isActive",
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