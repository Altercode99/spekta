function loginFormUI() {
  let e = [
    {
      type: "label",
      label:
        "<div id='logo-kf' style='text-align:center'><img src='public/img/logo-kf.png' style='width:65%' /></div>",
    },
    {
      type: "label",
      label:
        "<div style='width:100%;text-align:center'><marquee>SISTEM PELAYANAN ELEKTRONIK KIMIA FARMA PLANT JAKARTA</marquee></div>",
    },
    {
      type: "block",
      list: [
        {
          type: "label",
          label: "<img src='public/codebase/icons/login.png' />",
        },
        { type: "newcolumn" },
        {
          type: "block",
          offsetTop: 10,
          list: [
            { type: "settings" },
            {
              type: "input",
              name: "username",
              label: "Username",
              labelWidth: 80,
              validate: "NotEmpty",
            },
            {
              type: "password",
              name: "password",
              label: "Password",
              offsetTop: 10,
              labelWidth: 80,
              validate: "NotEmpty",
            },
          ],
        },
      ],
    },
    {
      type: "block",
      offsetTop: 10,
      offsetLeft: 15,
      list: [
        {
          type: "button",
          name: "login",
          className: "button_ok",
          value: "Login",
          offsetLeft: 40,
        },
        { type: "newcolumn" },
        {
          type: "button",
          name: "cancel",
          className: "button_no",
          value: "Cancel",
          offsetLeft: 40,
        },
      ],
    },
    {
      type: "label",
      label:
        "<div id='logo-kf' style='text-align:center'><img src='public/img/logo-spekta.png' style='width:65%' /></div>",
    },
    {
      type: "label",
      label: `<div style='width:100%;display:flex;flex-direction:column;justify-content:space-between;align-items:center;border-top: 1px solid #ccc'><span style='font-family:sans-serif;margin-bottom:10px;margin-top:10px'>PT. KIMIA FARMA PLANT JAKARTA</span><span style='font-family:sans-serif'>Developed By Hardware & Network</span><span style='margin-top:10px;font-family:sans-serif'>©Copyright ${new Date().getFullYear()}</span></div>`,
    },
  ];
  function t() {
    loginForm.setItemFocus("password"),
      !0 !== loginForm.validate()
        ? dhtmlx.message({
            type: "error",
            text: "Username & Password tidak boleh kosong",
            expire: 1e4,
          })
        : document.getElementById("realForm").submit();
  }
  (loginForm = new dhtmlXForm("Container", e)),
    loginForm.enableLiveValidation(!0),
    loginForm.setFocusOnFirstActive("password"),
    loginForm.attachEvent("onButtonClick", function (e) {
      "login" == e
        ? t()
        : "cancel" == e && (window.location.href = "index.php");
    }),
    loginForm.attachEvent("onEnter", function (e) {
      t();
    });
}
function submitCallback(e, t) {
  switch (e) {
    case "failed":
      loginForm.lock(),
        this.attempts ? this.attempts++ : (this.attempts = 1),
        setTimeout(function () {
          loginForm.unlock();
        }, 1e3 * this.attempts),
        dhtmlx.message({
          text: "Username atau Password salah<br>",
          type: "error",
          expire: 6e3,
        }),
        loginForm.setItemValue("password", "");
      break;
    case "failedbanned":
      dhtmlx.modalbox({
        type: "alert-error",
        title: "Login Failed",
        text: "Salah sebanyak 5X, tutup browser anda dan buka kembali",
        buttons: ["OK"],
      });
      break;
    case "success":
      let e = JSON.parse(t);
      (userLogged = {
        userId: e.userId,
        username: e.username,
        roleId: e.roleId,
        role: e.role,
        empNip: e.empNip,
        empId: e.empId,
        empName: e.empName,
        deptId: e.deptId,
        department: e.department,
        subId: e.subId,
        subDepartment: e.subDepartment,
        rankId: e.rankId,
        rank: e.rank,
        divId: e.divId,
        division: e.division,
        empLoc: e.empLoc,
        locName: e.locName,
        picOvertime: e.picOvertime,
        pltDeptId: e.pltDeptId,
        pltDepartment: e.pltDepartment,
        pltSubId: e.pltSubId,
        pltSubDepartment: e.pltSubDepartment,
        pltDivId: e.pltDivId,
        pltDivision: e.pltDivision,
        pltRankId: e.pltRankId,
      }),
        localStorage.setItem("isLogin", !0),
        loginForm.unload(),
        $("#mainCanvas").removeClass("anim_gradient"),
        $("#Container").remove(),
        (spinner = new Spinner().spin(document.body)),
        loadExternalJS("layout"),
        loadExternalJS("admin"),
        loadPrivilageViewJS();
  }
}
