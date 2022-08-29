$(".alert").alert();

$("#select-all").click(function (event) {
  if (this.checked) {
    $(":checkbox").each(function () {
      this.checked = true;
    });
  } else {
    $(":checkbox").each(function () {
      this.checked = false;
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  $.getJSON("utils/counter.php", {}, function (data) {
    var dataC = eval(data);
    var clients = [];
    $.each(dataC.countries, function () {
      clients[this.id] = this.value;
    });

    var shadeColors = [];
    var fillColors = [];
    var themeColor = $("#theme_mode").val();

    shadeColors["dark"] = "#375a7f";
    shadeColors["light"] = "#007bff";

    fillColors["dark"] = "#2b4764";
    fillColors["light"] = "#0056b3";

    $("#clientmap").vectorMap({
      map: "world_mill",
      backgroundColor: "transparent",
      series: {
        regions: [
          {
            values: clients,
            scale: ["#e6e6e6", shadeColors[themeColor]],
            normalizeFunction: "polynomial",
          },
        ],
      },
      regionStyle: {
        hover: {
          fill: fillColors[themeColor],
          cursor: "pointer",
        },
      },

      onRegionTipShow: function (e, el, code) {
        if (typeof clients[code] != "undefined") {
          el.html(el.html() + " (" + clients[code] + " Clients)");
        }
      },
    });
  });
});
