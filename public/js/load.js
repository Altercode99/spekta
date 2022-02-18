function loadExternalJS(type) {
  head.load(js_dhtmlx_export, function () {
    let folder = `index.php?c=AppController&m=loadViews&type=${type}&file=`;
    body = document.getElementsByTagName("head")[0];
    $.ajax({
      url: `index.php?c=AppController&m=listViews&type=${type}`,
      dataType: "json",
      success: function (data) {
        data.forEach(function (item) {
          $("<script />", {
            type: "text/javascript",
            src: "" + folder + item + "",
          }).appendTo(body);
        });
      },
      complete: function () {
        spinner.stop();
      },
    });
  });
}

function loadPrivilageViewJS() {
  head.load(js_dhtmlx_export, function () {
    let folder = `index.php?c=AppController&m=loadPrivilageViews&file=`;
    body = document.getElementsByTagName("head")[0];
    $.ajax({
      url: `index.php?c=AppController&m=listPrivilageViews`,
      dataType: "json",
      success: function (data) {
        if (userLogged.role === "admin") {
          appMenus = data.menu;
          data.files.forEach(function (item) {
            $("<script />", {
              type: "text/javascript",
              src: "" + folder + item + "",
            }).appendTo(body);
          });
        } else {
          appMenus = data.menu;
          appAccordions = data.accordions;
          appTrees = data.trees;
          data.files.forEach(function (item) {
            $("<script />", {
              type: "text/javascript",
              src: "" + folder + item + "",
            }).appendTo(body);
          });
        }
      },
      complete: function () {
        spinner.stop();
      },
    });
  });
}
