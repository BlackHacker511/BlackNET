// Call the dataTables jQuery plugin
$(document).ready(function () {
  var table = $("#dataTable").DataTable({
    ordering: true,

    select: {
      style: "multi",
    },
    order: [[1, null]],
    columnDefs: [
      {
        targets: 0,
        orderable: false,
      },
    ],
  });

  $("a.toggle-vis").on("click", function (e) {
    e.preventDefault();

    // Get the column API object
    var column = table.column($(this).attr("data-column"));

    // Toggle the visibility
    column.visible(!column.visible());
  });
});
