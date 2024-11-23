const owners_table = $("#owners_table").DataTable({
    ajax: {
        url: "./queries/to_get_all_the_owners",
        type: "GET",
        dataSrc: "",
    },
    columnDefs: [{
            targets: 0,
            data: "created_at",
        }, {
            targets: 1,
            data: {
                "first_name": "first_name",
                "second_name": "second_name",
                "last_name": "last_name",
                "owner_id": "owner_id"
            },
            render: ((data) => {
                return `<a href="./page?owners_id=${data.owner_id}">${data.first_name} ${data.second_name} ${data.last_name}</a>`
            })
        }, {
            targets: 2,
            data: "phone_number",
        },

        {
            targets: 3,
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