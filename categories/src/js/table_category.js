const category_table = $("#category_table").DataTable({
  ajax: {
    url: "./queries/to_get_all_the_category_in_the_database.php",
    type: "GET",
    dataSrc: "",
  },
  columnDefs: [{
      data: "created_at",
      targets: 0,
    },
    {
      targets: 1,
      data: {
        category_name: "category_name",
        category_id: "category_id",
      },
      render: (data) => {
        return `<a href="./view?cid=${data.category_id}">${data.category_name}</a>`;
      },
    },
    {
      targets: 2,
      data: "isActive",
      render: (data) => {
        if (data == 1) {
          return `<span class="badge rounded-pill text-bg-success">Active</span>`;
        } else {
          return `<span class="badge rounded-pill text-bg-danger">InActive</span>`;
        }
      },
    },
  ],
});