const reqJson = (url, method, data, callback) => {
  if (method == "POST" || method == "DELETE") {
    $.ajax({
      url: url,
      type: method,
      dataType: "json",
      contentType: "appliation/json; charset=utf-8",
      data: JSON.stringify(data),
      success: (response) => callback(null, response),
      error: (err) => callback(true, err),
    });
  } else {
    $.ajax({
      url: url,
      type: method,
      dataType: "json",
      contentType: "appliation/json; charset=utf-8",
      success: (response) => callback(null, response),
      error: (err) => callback(true, err),
    });
  }
};

const reqJsonResponse = (url, method, data) => {
  return $.ajax({
    type: method,
    url: url,
    async: false,
    dataType: "json",
    contentType: "appliation/json; charset=utf-8",
    data: JSON.stringify(data),
    done: function (response) {
      return response;
    },
  }).responseJSON;
};

function ajaxLoad(url) {
  $.ajax({
    url: url,
    type: "GET",
  });
}

function erpController(directory, controller, method, params = null) {
  if (params) {
    let query;
    for (let param in params) {
      query = !query
        ? `&${param}=${params[param]}`
        : query + `&${param}=${params[param]}`;
    }
    return `index.php?d=erp/${directory}&c=${controller}&m=${method}${query}`;
  } else {
    return `index.php?d=erp/${directory}&c=${controller}&m=${method}`;
  }
}

function mainController(controller, method, params = null) {
  if (params) {
    let query;
    for (let param in params) {
      query = `&${param}=${params[param]}`;
    }
    return `index.php?c=${controller}&m=${method}${query}`;
  } else {
    return `index.php?c=${controller}&m=${method}`;
  }
}

function fetchFormData(
  url,
  formdata,
  filename = null,
  next = null,
  next2 = null
) {
  reqJson(url, "GET", null, (err, res) => {
    if (filename && filename.length > 0) {
      var files = [];
    }

    res.field.map((field) => {
      formdata.setItemValue(field, res.data[field]);
      if (filename && filename.length > 0) {
        filename.map((file) => file == field && files.push(res.data[field]));
      }
    });

    if (filename && files.length > 0 && next) {
      next(files);
    }

    if (next2) {
      next2();
    }
  });
}

function clearComboReload(formdata, id, url) {
  let combo = formdata.getCombo(id);
  combo.clearAll();
  combo.addOption([[0, ""]]);
  combo.load(url);
  combo.selectOption(0);
}

function clearComboNoReload(formdata, id) {
  let combo = formdata.getCombo(id);
  combo.clearAll();
  combo.addOption([[0, ""]]);
  combo.selectOption(0);
}

function clearComboOptions(formdata, id) {
  let combo = formdata.getCombo(id);
  combo.selectOption(0);
}

function clearAllForm(form, combo = null, linkCombo = null, except = null) {
  form.forEachItem(function (name) {
    if (except && except.includes(name)) {
      return;
    } else {
      let type = form.getItemType(name);
      if (
        type === "input" ||
        type === "calendar" ||
        type === "password" ||
        type === "checkbox"
      ) {
        if (type === "checkbox") {
          form.setItemValue(name, "0");
        } else {
          form.setItemValue(name, "");
        }
      } else if (type === "combo") {
        if (combo && combo[name] !== undefined) {
          if (combo[name].reload) {
            clearComboReload(form, name, combo[name].url);
          } else {
            clearComboNoReload(form, name);
          }
        } else {
          if (linkCombo && linkCombo[name] === true) {
            clearComboNoReload(form, name);
          } else {
            clearComboOptions(form, name);
          }
        }
      }
    }
  });
}

function setDisable(button, form, layoutLoading = null) {
  button.map((name) => form.disableItem(name));
  if (layoutLoading) {
    layoutLoading.progressOn();
  }
}

function setEnable(button, form, layoutLoading) {
  button.map((name) => form.enableItem(name));
  if (layoutLoading) {
    layoutLoading.progressOff();
  }
}

function setEscape(value) {
  return unescape(encodeURIComponent(value));
}

function tabsStyle(img, title, style = null) {
  let path = `./public/codebase/icons/${img}`;
  return `<span style='background-repeat: no-repeat; background-image: url(${path}); background-position: 0px 1px; padding-left: 20px; padding-bottom:10px; ${style}'>${title}</span>`;
}

function reqAction(grid, reqUrl, fieldId, callback) {
  if (!grid.getSelectedRowId()) {
    return dhtmlx.message({
      type: "error",
      text: "Pilih baris yang mau dihapus !",
    });
  }

  var selectedRows = grid.getSelectedRowId();
  if (selectedRows.indexOf(",") > -1) {
    dhtmlx.message({
      type: "error",
      expire: 10000,
      text: "<img src='./public/codebase/icons/messagebox_warning.png'> Hati-hati menghapus lebih dari satu",
    });
  }

  var datas = [];
  selectedRows
    .split(",")
    .map((id) => datas.push({ id, field: grid.cells(id, fieldId).getValue() }));

  dhtmlx.modalbox({
    type: "alert-error",
    title: "Konfirmasi",
    text: "Anda yakin ?",
    buttons: ["Ya", "Tidak"],
    callback: function (index) {
      if (index == 0) {
        const url = reqUrl;
        reqJson(url, "DELETE", { datas }, (err, res) => {
          if (!err) {
            if (res.status === "success") {
              callback(null, res);
            }
          } else {
            callback(err, null);
          }
        });
      }
    },
  });
}

function reqConfirm(grid, reqUrl, fieldId, callback) {
  if (!grid.getSelectedRowId()) {
    return dhtmlx.message({
      type: "error",
      text: "Pilih baris yang mau diproses !",
    });
  }

  var selectedRows = grid.getSelectedRowId();
  if (selectedRows.indexOf(",") > -1) {
    dhtmlx.message({
      expire: 10000,
      text: "<img src='./public/codebase/icons/messagebox_warning.png'> Hati-hati memproses lebih dari satu",
    });
  }

  var datas = [];
  selectedRows
    .split(",")
    .map((id) => datas.push({ id, field: grid.cells(id, fieldId).getValue() }));

  dhtmlx.modalbox({
    type: "alert-warning",
    title: "Konfirmasi",
    text: "Anda yakin ?",
    buttons: ["Ya", "Tidak"],
    callback: function (index) {
      if (index == 0) {
        const url = reqUrl;
        reqJson(url, "DELETE", { datas }, (err, res) => {
          if (!err) {
            if (res.status === "success") {
              callback(null, res);
            }
          } else {
            callback(err, null);
          }
        });
      }
    },
  });
}

function toDownload(fileUrl) {
  dhtmlx.modalbox({
    type: "alert-error",
    title: "Konfirmasi",
    text: "Download file ?",
    buttons: ["Ya", "Tidak"],
    callback: function (index) {
      if (index == 0) {
        download(fileUrl);
      }
    },
  });
}

function mToMonth(m) {
  let month = {
    1: "Januari",
    2: "Februari",
    3: "Maret",
    4: "April",
    5: "Mei",
    6: "Juni",
    7: "Juli",
    8: "Agustus",
    9: "September",
    10: "Oktober",
    11: "November",
    12: "Desember",
  };

  return month[m];
}

function indoDate(date) {
  let localDate = date.toLocaleString();
  let newDate = localDate.split(",")[0];
  let firstFix = newDate.split("/");
  return firstFix[1] + " " + mToMonth(firstFix[0]) + " " + firstFix[2];
}

function isFormNumeric(form, columns) {
  form.attachEvent("onChange", function (name, value, state) {
    columns.map((column) => {
      if (column == name && state !== "") {
        form.setItemValue(column, value.replace(/\D/g, ""));
      }
    });
  });
}

function isHaveMenu(mName) {
  return appMenus.indexOf(mName) > -1 ? true : false;
}

function isHaveAcc(acc) {
  if (userLogged.role === "admin") {
    return true;
  } else {
    return appAccordions.indexOf(acc) > -1 ? true : false;
  }
}

function isHaveTrees(tree) {
  if (userLogged.role === "admin") {
    return true;
  } else {
    return appTrees.indexOf(tree) > -1 ? true : false;
  }
}

function App(method, params) {
  return mainController("AppController", method, params);
}

function AppMaster(method, params) {
  return erpController("accmaster", "AppMaster1Controller", method, params);
}

function AppMaster2(method, params) {
  return erpController("accmaster", "AppMaster2Controller", method, params);
}

function User(method, params) {
  return erpController("accmaster", "UserController", method, params);
}

function Access(method, params) {
  return erpController("accmaster", "AccessController", method, params);
}

function AccessDept(method, params) {
  return erpController("accmaster", "AccessDeptController", method, params);
}

function Emp(method, params) {
  return erpController("hr", "EmpController", method, params);
}

function Sallary(method, params) {
  return erpController("hr", "SallaryController", method, params);
}

function Home(method, params) {
  return erpController("hr", "HomeController", method, params);
}

function Document(method, params) {
  return erpController("document", "DocumentController", method, params);
}

function RoomRev(method, params) {
  return erpController("other", "RoomRevController", method, params);
}

function VehicleRev(method, params) {
  return erpController("other", "VehicleRevController", method, params);
}

function Overtime(method, params) {
  return erpController("other", "OvertimeController", method, params);
}

function GAOther(method, params) {
  return erpController("g_affair", "OtherController", method, params);
}

function Dashboard(method, params) {
  return erpController("dashboard", "DashOvtController", method, params);
}

function DashMRoom(method, params) {
  return erpController("dashboard", "DashMRoomController", method, params);
}

function DashVehicle(method, params) {
  return erpController("dashboard", "DashVehicleController", method, params);
}

function Project(method, params) {
  return erpController("project", "ProjectController", method, params);
}

function Public(method, params) {
  return mainController("PublicController", method, params);
}

function fileUrl(filename) {
  return BASE_URL + "assets/files/" + filename;
}

function cleanSC(string) {
  let first = string.split("&amp;").join("&");
  let second = first.split("&amp;gt;").join(">");
  return second.split("&amp;lt;").join("<");
}

function isLogin() {
  const session = reqJsonResponse(App("checkSession"), "GET", null);
  if (session.status === "expired") {
    eaAlert("Session Expired!", "Silahkan login kembali");
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  } else {
    return true;
  }
}

function autoLogout() {
  let isLogin = localStorage.getItem("isLogin");
  if (!isLogin) {
    reqJsonResponse("index.php?c=AuthController&m=exitapp", "GET", null);
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  }
}

function sAlert(message, time = 3000) {
  return dhtmlx.message({ text: message, expire: time });
}

function eAlert(message, time = 5000) {
  return dhtmlx.message({ type: "error", text: message, expire: time });
}

function eaAlert(title, message) {
  setTimeout(() => {
    dhtmlx.message({
      type: "alert-error",
      title: title,
      text: message,
    });
  }, 100);
}

function eaWarning(title, message) {
  dhtmlx.message({
    type: "alert-warning",
    title: title,
    text: message,
  });
}

function createWindow(
  id = "Window_ID",
  title = "Window Title",
  width = 800,
  height = 500
) {
  const window = myWins.createWindow({
    id: id,
    text: title,
    left: 10,
    top: 20,
    center: true,
    modal: true,
    width: width,
    height: height,
  });

  window.keepInViewport(true);
  window.button("stick").hide();
  window.button("park").hide();
  window.button("minmax").hide();
  window.setMinDimension(width, height);
  window.setMaxDimension(width, height);

  return window;
}

function closeWindow(id) {
  myWins.window(id).skipMyCloseEvent = true;
  myWins.window(id).close();
}

function clearUploader(form, name) {
  form.getUploader(name).clear();
}

function isEmptyObj(obj) {
  return Object.keys(obj).length === 0;
}

function editorHandler(grid, numerics) {
  grid.attachEvent("onEditCell", function (stage, id, ind) {
    if (stage == 1 && numerics.indexOf(ind) > -1) {
      this.editor.obj.onkeypress = function (e) {
        let ValidChars = "0123456789";
        if (
          ValidChars.indexOf(String.fromCharCode((e || event).keyCode)) == -1
        ) {
          return false;
        } else {
          return true;
        }
      };
    } else {
      return true;
    }
  });
}

function isGridNumeric(grid, inds) {
  grid.attachEvent("onEditCell", function (stage, id, ind) {
    if (stage == 1 && inds.indexOf(ind) > -1) {
      this.editor.obj.onkeypress = function (e) {
        let ValidChars = "0123456789";
        if (
          ValidChars.indexOf(String.fromCharCode((e || event).keyCode)) == -1
        ) {
          return false;
        } else {
          return true;
        }
      };
    } else {
      return true;
    }
  });
}

function checkTime(startCombo, endCombo, button, form) {
  let newStart = parseFloat(startCombo.getSelectedValue().split(":").join("."));
  let newEnd = parseFloat(endCombo.getSelectedValue().split(":").join("."));

  setEnable(button, form);

  if (newEnd < newStart) {
    eaWarning(
      "Warning Waktu Lembur",
      "Waktu akhir lebih kecil dari waktu mulai, waktu akhir akan dihitung ke hari berikutnya!"
    );
    return true;
  }

  if (newEnd - newStart <= 1) {
    eaAlert(
      "Kesalahan Waktu Lembur",
      "Waktu lembur minimal adalah 1 jam! <br/><b>TOMBOL DISABLED</>"
    );
    setDisable(button, form);
    return false;
  }

  if (newEnd === newStart) {
    eaAlert(
      "Kesalahan Waktu Lembur",
      "Waktu selesai harus lebih besar dari waktu mulai! <br/><b>TOMBOL DISABLED</>"
    );
    setDisable(button, form);
    return false;
  }
}

function timeDiffCalc(dateFuture, dateNow) {
  let diffInMilliSeconds = Math.abs(dateFuture - dateNow) / 1000;

  const days = Math.floor(diffInMilliSeconds / 86400);
  diffInMilliSeconds -= days * 86400;

  const hours = Math.floor(diffInMilliSeconds / 3600) % 24;
  diffInMilliSeconds -= hours * 3600;

  const minutes = Math.floor(diffInMilliSeconds / 60) % 60;
  diffInMilliSeconds -= minutes * 60;

  return {
    days,
    hours,
    minutes,
  };
}

function daysInMonth(month, year) {
  return new Date(year, month, 0).getDate();
}

function filterForMonth(date) {
  let d = new Date(date);
  let newDate = [
    d.getFullYear(),
    ("0" + (d.getMonth() + 1)).slice(-2),
    ("0" + d.getDate()).slice(-2),
  ];

  let day = daysInMonth(newDate[1], newDate[0]);

  return {
    start: newDate[0] + "-" + newDate[1] + "-01",
    end: newDate[0] + "-" + newDate[1] + "-" + day,
  };
}

function checkFilterDate(start, end) {
  if (start == "" || end == "") {
    eAlert("Tanggal tidak lengkap!");
  }

  let dt1 = new Date(start);
  let dt2 = new Date(end);

  let time_difference = dt2.getTime() - dt1.getTime();
  let result = time_difference / (1000 * 60 * 60 * 24);

  if (dt2.getTime() < dt1.getTime()) {
    eaAlert(
      "Kesalahan Filter",
      "Waktu akhir harus lebih besar dari waktu awal!"
    );
    return false;
  }

  if (result > 31) {
    eaAlert("Pembatasan Filter", "Maksimum pencarian adalah 31 hari!");
    return false;
  }

  return true;
}

function numberFormat(number) {
  let formatter = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  });
  return number > 0
    ? formatter.format(number).replace("$", "")
    : formatter.format(0).replace("$", "");
}

function sumGridToElement(form, column, id, id2 = null, type = "money") {
  var element = document.getElementById(id);
  if (id2) {
    var element2 = document.getElementById(id2);
  }

  let total = 0;
  if (type == "money") {
    for (let i = 0; i < form.getRowsNum(); i++) {
      let premi = form.cells2(i, column).getValue();
      var clean = premi.replaceAll(".", "");
      var clean2 = parseFloat(clean.replaceAll(",", "."));
      total += clean2;
    }

    element.innerHTML = numberFormat(total);
    if (id2) {
      element2.innerHTML = "Rp. " + numberFormat(total);
    }
  } else {
    for (let i = 0; i < form.getRowsNum(); i++) {
      let premi = form.cells2(i, column).getValue();
      if (type == "float") {
        total += parseFloat(premi);
      } else if (type == "int") {
        total += parseInt(premi);
      } else {
        total += premi;
      }
    }

    if (type == "int") {
      element.innerHTML = total;
      if (id2) {
        element2.innerHTML = total;
      }
    } else {
      element.innerHTML = numberFormat(total);
      if (id2) {
        element2.innerHTML = numberFormat(total);
      }
    }
  }

  return total;
}

function genCompareYear(yearOne, yearTwo) {
  let date = new Date();
  let y1 = `<div style='width:100%;display:flex'>
            <div style='width:100%;margin-left: 10px'> Tahun Pertama: <select id='${yearOne}' style='height:22px;margin-top:3px'>`;
  for (let i = 2021; i <= date.getFullYear(); i++) {
    if (date.getFullYear() - 1 === i) {
      y1 = y1 + `<option selected value='${i}'>${i}</option>`;
    } else {
      y1 = y1 + `<option value='${i}'>${i}</option>`;
    }
  }
  y1 = y1 + "</select></div>";

  y1 =
    y1 +
    `<div style='width:100%;margin-left: 10px'> Tahun Kedua: <select id='${yearTwo}' style='height:22px;margin-top:3px'>`;
  for (let i = 2021; i <= date.getFullYear(); i++) {
    if (date.getFullYear() === i) {
      y1 = y1 + `<option selected value='${i}'>${i}</option>`;
    } else {
      y1 = y1 + `<option value='${i}'>${i}</option>`;
    }
  }
  y1 = y1 + "</select></div></div>";
  return y1;
}

function genSelectMonth(yearName, monthName) {
  let date = new Date();
  let month = date.getMonth() + 1;
  let year = date.getFullYear();

  let months = {
    1: "Januari",
    2: "Februari",
    3: "Maret",
    4: "April",
    5: "Mei",
    6: "Juni",
    7: "Juli",
    8: "Agustus",
    9: "September",
    10: "Oktober",
    11: "November",
    12: "Desember",
  };
  let options =
    "<span style='width:100%'> Bulan: <select id='" +
    monthName +
    "' style='height:22px;margin-top:3px'>";
  for (let i = 1; i <= 12; i++) {
    if (month === i) {
      options = options + `<option selected value='${i}'>${months[i]}</option>`;
    } else {
      options = options + `<option value='${i}'>${months[i]}</option>`;
    }
  }
  options = options + "</select></span>";

  let optionsYear =
    "<span style='width:100%;margin-left: 10px'> Tahun: <select id='" +
    yearName +
    "' style='height:22px;margin-top:3px'>";
  for (let i = 2021; i <= date.getFullYear(); i++) {
    if (year === i) {
      optionsYear = optionsYear + `<option selected value='${i}'>${i}</option>`;
    } else {
      optionsYear = optionsYear + `<option value='${i}'>${i}</option>`;
    }
  }
  optionsYear = optionsYear + "</select></span>";

  return options + "" + optionsYear;
}

function genWorkTime(times, startIndex, endIndex) {
  var newStartTime = [];
  var newEndTime = [];
  var filterStart = [];
  var filterEnd = [];
  if (endIndex < startIndex) {
    times.map((time, index) => {
      if (index >= startIndex) {
        if (
          time.value !== "12:00" &&
          time.value !== "12:30" &&
          time.value !== "18:00" &&
          time.value !== "00:00" &&
          time.value !== "00:30" &&
          time.value !== "04:30" &&
          time.value !== "05:00"
        ) {
          newStartTime.push({
            text: time.text,
            value: time.value,
          });
          filterStart.push(time.value);
        }
      }

      if (index <= endIndex) {
        if (
          time.value !== "12:30" &&
          time.value !== "18:30" &&
          time.value !== "00:30" &&
          time.value !== "05:00"
        ) {
          newEndTime.push({
            text: time.text,
            value: time.value,
          });
          filterEnd.push(time.value);
        }
      }
    });
  } else {
    times.map((time, index) => {
      if (index >= startIndex && index <= endIndex) {
        if (
          time.value !== "12:00" &&
          time.value !== "12:30" &&
          time.value !== "18:00" &&
          time.value !== "18:30" &&
          time.value !== "00:00" &&
          time.value !== "00:30" &&
          time.value !== "04:30" &&
          time.value !== "05:00"
        ) {
          newStartTime.push({
            text: time.text,
            value: time.value,
          });
          filterStart.push(time.value);
        }

        if (
          time.value !== "12:30" &&
          time.value !== "18:30" &&
          time.value !== "00:30" &&
          time.value !== "05:00"
        ) {
          newEndTime.push({
            text: time.text,
            value: time.value,
          });
          filterEnd.push(time.value);
        }
      }
    });
  }

  return {
    newStartTime,
    newEndTime,
    filterStart,
    filterEnd,
  };
}

function createTime(type = "overtime") {
  var startTimes = [];
  var filterStartTime = [];
  var endTimes = [];
  var filterEndTime = [];
  var times = [];
  var filterTime = [];
  var minutes = ["00", "30"];
  for (let i = 0; i <= 23; i++) {
    minutes.map((min) => {
      let newI = i < 10 ? "0" + i : i;
      let time = newI + ":" + min;
      if (type === "overtime") {
        if (
          time !== "12:00" &&
          time !== "12:30" &&
          time !== "18:00" &&
          time !== "18:30" &&
          time !== "00:00" &&
          time !== "00:30" &&
          time !== "04:30" &&
          time !== "05:00"
        ) {
          startTimes.push({ value: time, text: time });
          filterStartTime.push(time);
        }
        if (
          time !== "12:30" &&
          time !== "18:30" &&
          time !== "00:30" &&
          time !== "05:00"
        ) {
          endTimes.push({ value: time, text: time });
          filterEndTime.push(time);
        }
      } else if (type === "full") {
        startTimes.push({ value: time, text: time });
        filterStartTime.push(time);

        endTimes.push({ value: time, text: time });
        filterEndTime.push(time);
      }

      times.push({ value: time, text: time });
      filterTime.push(time);
    });
  }

  return {
    startTimes,
    filterStartTime,
    endTimes,
    filterEndTime,
    times,
    filterTime,
  };
}

function getCurrentTime(form, startCell, endCell) {
  let startTime = form
    .cells(form.getSelectedRowId(), startCell)
    .getValue()
    .split(" ");
  let endTime = form
    .cells(form.getSelectedRowId(), endCell)
    .getValue()
    .split(" ");
  let startSplit = startTime[3].split(":");
  let endSplit = endTime[3].split(":");
  let fixStartTime = startSplit[0] + ":" + startSplit[1];
  let fixEndTime = endSplit[0] + ":" + endSplit[1];

  let labelStart = startTime[0] + " " + startTime[1] + " " + startTime[2];
  let labelEnd = endTime[0] + " " + endTime[1] + " " + endTime[2];

  return {
    start: fixStartTime,
    end: fixEndTime,
    labelStart,
    labelEnd,
  };
}

function checkRevisionTime(times, start, end, button, form) {
  let startIndex = times.indexOf(start);
  let endIndex = times.indexOf(end);
  setEnable(button, form);

  if (endIndex > startIndex) {
    let div = endIndex - startIndex;
    if (div < 2) {
      eaAlert(
        "Kesalahan Waktu Lembur",
        "Waktu lembur minimal adalah 1 jam! <br/><b>TOMBOL DISABLED</>"
      );
      setDisable(button, form);
    }
  } else {
    eaAlert(
      "Kesalahan Waktu Lembur",
      "Waktu selesai harus lebih besar dari waktu mulai! <br/><b>TOMBOL DISABLED</>"
    );
    setDisable(button, form);
  }
}

function nameOfMonth(month) {
  let months = {
    1: "Januari",
    2: "Februari",
    3: "Maret",
    4: "April",
    5: "Mei",
    6: "Juni",
    7: "Juli",
    8: "Agustus",
    9: "September",
    10: "Oktober",
    11: "November",
    12: "Desember",
  };

  return months[month];
}

function genColor(color, text) {
  return (
    "<i style='background:" +
    color +
    ";width:20px;color:#404040;border-radius:5px;padding-left:5px;padding-right:5px;'>" +
    text +
    "</i>"
  );
}

function legendGrid() {
  return {
    email_send:
      "<span style='margin-left:5px;margin-right:5px'>Status: " +
      genColor("#8bc38f", "Terkirim") +
      "  " +
      genColor("#ccc", "Berlum Terkirim") +
      "</span>",
    m_room_rev:
      "<span style='margin-left:5px;margin-right:5px'>Status: " +
      genColor("#75b175", "Approve") +
      "  " +
      genColor("#c94b62", "Rejected") +
      "  " +
      genColor("#dda94a", "Closed") +
      " | Kolom Konfirmasi Driver: " +
      genColor("#75b175", "Disetujui") +
      "  " +
      genColor("#dda94a", "Menolak") +
      "</span>",
    revision_overtime:
      "<span style='margin-left:5px;margin-right:5px'>Revisi: " +
      genColor("#efd898", "Process") +
      "  " +
      genColor("#d7a878", "Canceled") +
      "  " +
      genColor("#c94b62", "Rejected") +
      "  " +
      genColor("#7ecb87", "Closed") +
      "</span>",
    hr_verified_overtime:
      "<span style='margin-left:5px;margin-right:5px'>Payment: " +
      genColor("#c18cdd", "Verified") +
      " | Overtime: " +
      genColor("#efd898", "Hari Libur") +
      "  " +
      genColor("#7ecbf1", "Libur Nasional") +
      "</span>",
    hr_report_overtime:
      "<span style='margin-left:5px;margin-right:5px'>Payment: " +
      genColor("#c18cdd", "Verified") +
      "  " +
      genColor("#c8e71c", "Pending") +
      " | Ulasan: " +
      genColor("#75b175", "Sudah diulas") +
      " | Overtime: " +
      genColor("#efd898", "Hari Libur") +
      "  " +
      genColor("#7ecbf1", "Libur Nasional") +
      "</span>",
    report_overtime:
      "<span style='margin-left:5px;margin-right:5px'>Payment: " +
      genColor("#c18cdd", "Verified") +
      " | Ulasan: " +
      genColor("#75b175", "Sudah diulas") +
      " | Overtime: " +
      genColor("#efd898", "Hari Libur") +
      "  " +
      genColor("#7ecbf1", "Libur Nasional") +
      "</span>",
    input_overtime:
      "<span style='margin-left:5px;margin-right:5px' >Overtime: " +
      genColor("#efd898", "Hari Libur") +
      "  " +
      genColor("#7ecbf1", "Libur Nasional") +
      "</span>",
    approval_overtime:
      "<span style='margin-left:5px;margin-right:5px'> Approval: " +
      genColor("#cedb10", "Approval Supervisor") +
      "  " +
      genColor("#db8a10", "Approval ASMAN") +
      "  " +
      genColor("#d968b1", "Approval Manager") +
      " | Overtime: " +
      genColor("#efd898", "Hari Libur") +
      "  " +
      genColor("#7ecbf1", "Libur Nasional") +
      "</span>",
  };
}
