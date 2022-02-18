const reqJson = (e, t, r, n) => {
    "POST" == t || "DELETE" == t
      ? $.ajax({
          url: e,
          type: t,
          dataType: "json",
          contentType: "appliation/json; charset=utf-8",
          data: JSON.stringify(r),
          success: (e) => n(null, e),
          error: (e) => n(!0, e),
        })
      : $.ajax({
          url: e,
          type: t,
          dataType: "json",
          contentType: "appliation/json; charset=utf-8",
          success: (e) => n(null, e),
          error: (e) => n(!0, e),
        });
  },
  reqJsonResponse = (e, t, r) =>
    $.ajax({
      type: t,
      url: e,
      async: !1,
      dataType: "json",
      contentType: "appliation/json; charset=utf-8",
      data: JSON.stringify(r),
      done: function (e) {
        return e;
      },
    }).responseJSON;
function ajaxLoad(e) {
  $.ajax({ url: e, type: "GET" });
}
function erpController(e, t, r, n = null) {
  if (n) {
    let o;
    for (let e in n) o = o ? o + `&${e}=${n[e]}` : `&${e}=${n[e]}`;
    return `index.php?d=erp/${e}&c=${t}&m=${r}${o}`;
  }
  return `index.php?d=erp/${e}&c=${t}&m=${r}`;
}
function mainController(e, t, r = null) {
  if (r) {
    let n;
    for (let e in r) n = `&${e}=${r[e]}`;
    return `index.php?c=${e}&m=${t}${n}`;
  }
  return `index.php?c=${e}&m=${t}`;
}
function fetchFormData(e, t, r = null, n = null, o = null) {
  reqJson(e, "GET", null, (e, a) => {
    if (r && r.length > 0) var l = [];
    a.field.map((e) => {
      t.setItemValue(e, a.data[e]),
        r && r.length > 0 && r.map((t) => t == e && l.push(a.data[e]));
    }),
      r && l.length > 0 && n && n(l),
      o && o();
  });
}
function clearComboReload(e, t, r) {
  let n = e.getCombo(t);
  n.clearAll(), n.addOption([[0, ""]]), n.load(r), n.selectOption(0);
}
function clearComboNoReload(e, t) {
  let r = e.getCombo(t);
  r.clearAll(), r.addOption([[0, ""]]), r.selectOption(0);
}
function clearComboOptions(e, t) {
  e.getCombo(t).selectOption(0);
}
function clearAllForm(e, t = null, r = null, n = null) {
  e.forEachItem(function (o) {
    if (!n || !n.includes(o)) {
      let n = e.getItemType(o);
      "input" === n || "calendar" === n || "password" === n || "checkbox" === n
        ? "checkbox" === n
          ? e.setItemValue(o, "0")
          : e.setItemValue(o, "")
        : "combo" === n &&
          (t && void 0 !== t[o]
            ? t[o].reload
              ? clearComboReload(e, o, t[o].url)
              : clearComboNoReload(e, o)
            : r && !0 === r[o]
            ? clearComboNoReload(e, o)
            : clearComboOptions(e, o));
    }
  });
}
function setDisable(e, t, r = null) {
  e.map((e) => t.disableItem(e)), r && r.progressOn();
}
function setEnable(e, t, r) {
  e.map((e) => t.enableItem(e)), r && r.progressOff();
}
function setEscape(e) {
  return unescape(encodeURIComponent(e));
}
function tabsStyle(e, t, r = null) {
  return `<span style='background-repeat: no-repeat; background-image: url(${`./public/codebase/icons/${e}`}); background-position: 0px 1px; padding-left: 20px; padding-bottom:10px; ${r}'>${t}</span>`;
}
function reqAction(e, t, r, n) {
  if (!e.getSelectedRowId())
    return dhtmlx.message({
      type: "error",
      text: "Pilih baris yang mau dihapus !",
    });
  var o = e.getSelectedRowId();
  o.indexOf(",") > -1 &&
    dhtmlx.message({
      type: "error",
      expire: 1e4,
      text: "<img src='./public/codebase/icons/messagebox_warning.png'> Hati-hati menghapus lebih dari satu",
    });
  var a = [];
  o.split(",").map((t) => a.push({ id: t, field: e.cells(t, r).getValue() })),
    dhtmlx.modalbox({
      type: "alert-error",
      title: "Konfirmasi",
      text: "Anda yakin ?",
      buttons: ["Ya", "Tidak"],
      callback: function (e) {
        if (0 == e) {
          reqJson(t, "DELETE", { datas: a }, (e, t) => {
            e ? n(e, null) : "success" === t.status && n(null, t);
          });
        }
      },
    });
}
function reqConfirm(e, t, r, n) {
  if (!e.getSelectedRowId())
    return dhtmlx.message({
      type: "error",
      text: "Pilih baris yang mau diproses !",
    });
  var o = e.getSelectedRowId();
  o.indexOf(",") > -1 &&
    dhtmlx.message({
      expire: 1e4,
      text: "<img src='./public/codebase/icons/messagebox_warning.png'> Hati-hati memproses lebih dari satu",
    });
  var a = [];
  o.split(",").map((t) => a.push({ id: t, field: e.cells(t, r).getValue() })),
    dhtmlx.modalbox({
      type: "alert-warning",
      title: "Konfirmasi",
      text: "Anda yakin ?",
      buttons: ["Ya", "Tidak"],
      callback: function (e) {
        if (0 == e) {
          reqJson(t, "DELETE", { datas: a }, (e, t) => {
            e ? n(e, null) : "success" === t.status && n(null, t);
          });
        }
      },
    });
}
function toDownload(e) {
  dhtmlx.modalbox({
    type: "alert-error",
    title: "Konfirmasi",
    text: "Download file ?",
    buttons: ["Ya", "Tidak"],
    callback: function (t) {
      0 == t && download(e);
    },
  });
}
function mToMonth(e) {
  return {
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
  }[e];
}
function indoDate(e) {
  let t = e.toLocaleString().split(",")[0].split("/");
  return t[1] + " " + mToMonth(t[0]) + " " + t[2];
}
function isFormNumeric(e, t) {
  e.attachEvent("onChange", function (r, n, o) {
    t.map((t) => {
      t == r && "" !== o && e.setItemValue(t, n.replace(/\D/g, ""));
    });
  });
}
function isHaveMenu(e) {
  return appMenus.indexOf(e) > -1;
}
function isHaveAcc(e) {
  return "admin" === userLogged.role || appAccordions.indexOf(e) > -1;
}
function isHaveTrees(e) {
  return "admin" === userLogged.role || appTrees.indexOf(e) > -1;
}
function App(e, t) {
  return mainController("AppController", e, t);
}
function AppMaster(e, t) {
  return erpController("accmaster", "AppMaster1Controller", e, t);
}
function AppMaster2(e, t) {
  return erpController("accmaster", "AppMaster2Controller", e, t);
}
function User(e, t) {
  return erpController("accmaster", "UserController", e, t);
}
function Access(e, t) {
  return erpController("accmaster", "AccessController", e, t);
}
function AccessDept(e, t) {
  return erpController("accmaster", "AccessDeptController", e, t);
}
function Emp(e, t) {
  return erpController("hr", "EmpController", e, t);
}
function Sallary(e, t) {
  return erpController("hr", "SallaryController", e, t);
}
function Home(e, t) {
  return erpController("hr", "HomeController", e, t);
}
function Document(e, t) {
  return erpController("document", "DocumentController", e, t);
}
function RoomRev(e, t) {
  return erpController("other", "RoomRevController", e, t);
}
function VehicleRev(e, t) {
  return erpController("other", "VehicleRevController", e, t);
}
function Overtime(e, t) {
  return erpController("other", "OvertimeController", e, t);
}
function GAOther(e, t) {
  return erpController("g_affair", "OtherController", e, t);
}
function Dashboard(e, t) {
  return erpController("dashboard", "DashOvtController", e, t);
}
function DashMRoom(e, t) {
  return erpController("dashboard", "DashMRoomController", e, t);
}
function DashVehicle(e, t) {
  return erpController("dashboard", "DashVehicleController", e, t);
}
function Project(e, t) {
  return erpController("project", "ProjectController", e, t);
}
function Public(e, t) {
  return mainController("PublicController", e, t);
}
function fileUrl(e) {
  return BASE_URL + "assets/files/" + e;
}
function cleanSC(e) {
  return e
    .split("&amp;")
    .join("&")
    .split("&amp;gt;")
    .join(">")
    .split("&amp;lt;")
    .join("<");
}
function isLogin() {
  if ("expired" !== reqJsonResponse(App("checkSession"), "GET", null).status)
    return !0;
  eaAlert("Session Expired!", "Silahkan login kembali"),
    setTimeout(() => {
      window.location.reload();
    }, 1e3);
}
function autoLogout() {
  localStorage.getItem("isLogin") ||
    (reqJsonResponse("index.php?c=AuthController&m=exitapp", "GET", null),
    setTimeout(() => {
      window.location.reload();
    }, 1e3));
}
function sAlert(e, t = 3e3) {
  return dhtmlx.message({ text: e, expire: t });
}
function eAlert(e, t = 5e3) {
  return dhtmlx.message({ type: "error", text: e, expire: t });
}
function eaAlert(e, t) {
  setTimeout(() => {
    dhtmlx.message({ type: "alert-error", title: e, text: t });
  }, 100);
}
function eaWarning(e, t) {
  dhtmlx.message({ type: "alert-warning", title: e, text: t });
}
function createWindow(e = "Window_ID", t = "Window Title", r = 800, n = 500) {
  const o = myWins.createWindow({
    id: e,
    text: t,
    left: 10,
    top: 20,
    center: !0,
    modal: !0,
    width: r,
    height: n,
  });
  return (
    o.keepInViewport(!0),
    o.button("stick").hide(),
    o.button("park").hide(),
    o.button("minmax").hide(),
    o.setMinDimension(r, n),
    o.setMaxDimension(r, n),
    o
  );
}
function closeWindow(e) {
  (myWins.window(e).skipMyCloseEvent = !0), myWins.window(e).close();
}
function clearUploader(e, t) {
  e.getUploader(t).clear();
}
function isEmptyObj(e) {
  return 0 === Object.keys(e).length;
}
function editorHandler(e, t) {
  e.attachEvent("onEditCell", function (e, r, n) {
    if (!(1 == e && t.indexOf(n) > -1)) return !0;
    this.editor.obj.onkeypress = function (e) {
      return (
        -1 != "0123456789".indexOf(String.fromCharCode((e || event).keyCode))
      );
    };
  });
}
function isGridNumeric(e, t) {
  e.attachEvent("onEditCell", function (e, r, n) {
    if (!(1 == e && t.indexOf(n) > -1)) return !0;
    this.editor.obj.onkeypress = function (e) {
      return (
        -1 != "0123456789".indexOf(String.fromCharCode((e || event).keyCode))
      );
    };
  });
}
function checkTime(e, t, r, n) {
  let o = parseFloat(e.getSelectedValue().split(":").join(".")),
    a = parseFloat(t.getSelectedValue().split(":").join("."));
  return (
    setEnable(r, n),
    a < o
      ? (eaWarning(
          "Warning Waktu Lembur",
          "Waktu akhir lebih kecil dari waktu mulai, waktu akhir akan dihitung ke hari berikutnya!"
        ),
        !0)
      : a - o <= 1
      ? (eaAlert(
          "Kesalahan Waktu Lembur",
          "Waktu lembur minimal adalah 1 jam! <br/><b>TOMBOL DISABLED</>"
        ),
        setDisable(r, n),
        !1)
      : a === o
      ? (eaAlert(
          "Kesalahan Waktu Lembur",
          "Waktu selesai harus lebih besar dari waktu mulai! <br/><b>TOMBOL DISABLED</>"
        ),
        setDisable(r, n),
        !1)
      : void 0
  );
}
function timeDiffCalc(e, t) {
  let r = Math.abs(e - t) / 1e3;
  const n = Math.floor(r / 86400);
  r -= 86400 * n;
  const o = Math.floor(r / 3600) % 24;
  r -= 3600 * o;
  const a = Math.floor(r / 60) % 60;
  return (r -= 60 * a), { days: n, hours: o, minutes: a };
}
function daysInMonth(e, t) {
  return new Date(t, e, 0).getDate();
}
function filterForMonth(e) {
  let t = new Date(e),
    r = [
      t.getFullYear(),
      ("0" + (t.getMonth() + 1)).slice(-2),
      ("0" + t.getDate()).slice(-2),
    ],
    n = daysInMonth(r[1], r[0]);
  return { start: r[0] + "-" + r[1] + "-01", end: r[0] + "-" + r[1] + "-" + n };
}
function checkFilterDate(e, t) {
  ("" != e && "" != t) || eAlert("Tanggal tidak lengkap!");
  let r = new Date(e),
    n = new Date(t),
    o = (n.getTime() - r.getTime()) / 864e5;
  return n.getTime() < r.getTime()
    ? (eaAlert(
        "Kesalahan Filter",
        "Waktu akhir harus lebih besar dari waktu awal!"
      ),
      !1)
    : !(o > 31) ||
        (eaAlert("Pembatasan Filter", "Maksimum pencarian adalah 31 hari!"),
        !1);
}
function numberFormat(e) {
  let t = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  });
  return e > 0 ? t.format(e).replace("$", "") : t.format(0).replace("$", "");
}
function sumGridToElement(e, t, r, n = null, o = "money") {
  var a = document.getElementById(r);
  if (n) var l = document.getElementById(n);
  let i = 0;
  if ("money" == o) {
    for (let r = 0; r < e.getRowsNum(); r++) {
      var u = e.cells2(r, t).getValue().replaceAll(".", "");
      i += parseFloat(u.replaceAll(",", "."));
    }
    (a.innerHTML = numberFormat(i)),
      n && (l.innerHTML = "Rp. " + numberFormat(i));
  } else {
    for (let r = 0; r < e.getRowsNum(); r++) {
      let n = e.cells2(r, t).getValue();
      i += "float" == o ? parseFloat(n) : "int" == o ? parseInt(n) : n;
    }
    "int" == o
      ? ((a.innerHTML = i), n && (l.innerHTML = i))
      : ((a.innerHTML = numberFormat(i)), n && (l.innerHTML = numberFormat(i)));
  }
  return i;
}
function genCompareYear(e, t) {
  let r = new Date(),
    n = `<div style='width:100%;display:flex'>\n            <div style='width:100%;margin-left: 10px'> Tahun Pertama: <select id='${e}' style='height:22px;margin-top:3px'>`;
  for (let e = 2021; e <= r.getFullYear(); e++)
    r.getFullYear() - 1 === e
      ? (n += `<option selected value='${e}'>${e}</option>`)
      : (n += `<option value='${e}'>${e}</option>`);
  (n += "</select></div>"),
    (n += `<div style='width:100%;margin-left: 10px'> Tahun Kedua: <select id='${t}' style='height:22px;margin-top:3px'>`);
  for (let e = 2021; e <= r.getFullYear(); e++)
    r.getFullYear() === e
      ? (n += `<option selected value='${e}'>${e}</option>`)
      : (n += `<option value='${e}'>${e}</option>`);
  return (n += "</select></div></div>"), n;
}
function genSelectMonth(e, t) {
  let r = new Date(),
    n = r.getMonth() + 1,
    o = r.getFullYear(),
    a = {
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
    },
    l =
      "<span style='width:100%'> Bulan: <select id='" +
      t +
      "' style='height:22px;margin-top:3px'>";
  for (let e = 1; e <= 12; e++)
    l +=
      n === e
        ? `<option selected value='${e}'>${a[e]}</option>`
        : `<option value='${e}'>${a[e]}</option>`;
  l += "</select></span>";
  let i =
    "<span style='width:100%;margin-left: 10px'> Tahun: <select id='" +
    e +
    "' style='height:22px;margin-top:3px'>";
  for (let e = 2021; e <= r.getFullYear(); e++)
    i +=
      o === e
        ? `<option selected value='${e}'>${e}</option>`
        : `<option value='${e}'>${e}</option>`;
  return (i += "</select></span>"), l + "" + i;
}
function genWorkTime(e, t, r) {
  var n = [],
    o = [],
    a = [],
    l = [];
  return (
    r < t
      ? e.map((e, i) => {
          i >= t &&
            "12:00" !== e.value &&
            "12:30" !== e.value &&
            "18:00" !== e.value &&
            "00:00" !== e.value &&
            "00:30" !== e.value &&
            "04:30" !== e.value &&
            "05:00" !== e.value &&
            (n.push({ text: e.text, value: e.value }), a.push(e.value)),
            i <= r &&
              "12:30" !== e.value &&
              "18:30" !== e.value &&
              "00:30" !== e.value &&
              "05:00" !== e.value &&
              (o.push({ text: e.text, value: e.value }), l.push(e.value));
        })
      : e.map((e, i) => {
          i >= t &&
            i <= r &&
            ("12:00" !== e.value &&
              "12:30" !== e.value &&
              "18:00" !== e.value &&
              "18:30" !== e.value &&
              "00:00" !== e.value &&
              "00:30" !== e.value &&
              "04:30" !== e.value &&
              "05:00" !== e.value &&
              (n.push({ text: e.text, value: e.value }), a.push(e.value)),
            "12:30" !== e.value &&
              "18:30" !== e.value &&
              "00:30" !== e.value &&
              "05:00" !== e.value &&
              (o.push({ text: e.text, value: e.value }), l.push(e.value)));
        }),
    { newStartTime: n, newEndTime: o, filterStart: a, filterEnd: l }
  );
}
function createTime(e = "overtime") {
  var t = [],
    r = [],
    n = [],
    o = [],
    a = [],
    l = [],
    i = ["00", "30"];
  for (let u = 0; u <= 23; u++)
    i.map((i) => {
      let s = (u < 10 ? "0" + u : u) + ":" + i;
      "overtime" === e
        ? ("12:00" !== s &&
            "12:30" !== s &&
            "18:00" !== s &&
            "18:30" !== s &&
            "00:00" !== s &&
            "00:30" !== s &&
            "04:30" !== s &&
            "05:00" !== s &&
            (t.push({ value: s, text: s }), r.push(s)),
          "12:30" !== s &&
            "18:30" !== s &&
            "00:30" !== s &&
            "05:00" !== s &&
            (n.push({ value: s, text: s }), o.push(s)))
        : "full" === e &&
          (t.push({ value: s, text: s }),
          r.push(s),
          n.push({ value: s, text: s }),
          o.push(s)),
        a.push({ value: s, text: s }),
        l.push(s);
    });
  return {
    startTimes: t,
    filterStartTime: r,
    endTimes: n,
    filterEndTime: o,
    times: a,
    filterTime: l,
  };
}
function getCurrentTime(e, t, r) {
  let n = e.cells(e.getSelectedRowId(), t).getValue().split(" "),
    o = e.cells(e.getSelectedRowId(), r).getValue().split(" "),
    a = n[3].split(":"),
    l = o[3].split(":");
  return {
    start: a[0] + ":" + a[1],
    end: l[0] + ":" + l[1],
    labelStart: n[0] + " " + n[1] + " " + n[2],
    labelEnd: o[0] + " " + o[1] + " " + o[2],
  };
}
function checkRevisionTime(e, t, r, n, o) {
  let a = e.indexOf(t),
    l = e.indexOf(r);
  if ((setEnable(n, o), l > a)) {
    l - a < 2 &&
      (eaAlert(
        "Kesalahan Waktu Lembur",
        "Waktu lembur minimal adalah 1 jam! <br/><b>TOMBOL DISABLED</>"
      ),
      setDisable(n, o));
  } else
    eaAlert(
      "Kesalahan Waktu Lembur",
      "Waktu selesai harus lebih besar dari waktu mulai! <br/><b>TOMBOL DISABLED</>"
    ),
      setDisable(n, o);
}
function nameOfMonth(e) {
  return {
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
  }[e];
}
function genColor(e, t) {
  return (
    "<i style='background:" +
    e +
    ";width:20px;color:#404040;border-radius:5px;padding-left:5px;padding-right:5px;'>" +
    t +
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
