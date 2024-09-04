$(document).ready(function () {
  "use strict";

  // Basic DataTable
  $("#basic-datatable").DataTable({
      keys: false, // Disable cell selection
      language: {
          paginate: {
              previous: "<i class='ri-arrow-left-s-line'>",
              next: "<i class='ri-arrow-right-s-line'>",
          },
      },
  });

});