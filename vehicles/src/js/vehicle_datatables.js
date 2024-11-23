const vehicle_table = $("#vehicle_table").DataTable({
  ajax: {
    url: "./queries/get_all_the_vehicles_in_th_db.php",
    type: "GET",
    dataSrc: "",
  },
  columnDefs: [{
      data: "created_at",
      targets: 0,
    },
    {
      targets: 1,
      data: "vehicle_name",
    },
    {
      targets: 2,
      data: {
        "registration_number": "registration_number",
        "vehicle_id": "vehicle_id"
      },
      render: ((data)=>{
          return `<a href="./pages/?v=${data.vehicle_id}">${data.registration_number}</a>`;
      })
    },
    {
      targets: 3,
      data: "category_name",
    },
    {
      targets: 4,
      data: "company_name",
    },
    {
      targets: 5,
      data: "isActive",
      render: ((data) => {
        if (data == 1) {
          return `<span class="badge rounded-pill text-bg-success">Active</span>`;
        } else {
          return `<span class="badge rounded-pill text-bg-danger">InActive</span>`;
        }
      })
    }
  ],
});