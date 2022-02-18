function loginFormUI() {
  let date = new Date();

  let loginFormData = [
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
      label: `<div style='width:100%;display:flex;flex-direction:column;justify-content:space-between;align-items:center;border-top: 1px solid #ccc'><span style='font-family:sans-serif;margin-bottom:10px;margin-top:10px'>PT. KIMIA FARMA PLANT JAKARTA</span><span style='font-family:sans-serif'>Developed By Hardware & Network</span><span style='margin-top:10px;font-family:sans-serif'>©Copyright ${date.getFullYear()}</span></div>`,
    },
  ];

  loginForm = new dhtmlXForm("Container", loginFormData);
  loginForm.enableLiveValidation(true);
  loginForm.setFocusOnFirstActive("password");

  function loginProcess() {
    loginForm.setItemFocus("password");
    if (loginForm.validate() !== true) {
      dhtmlx.message({
        type: "error",
        text: "Username & Password tidak boleh kosong",
        expire: 10000,
      });
    } else {
      document.getElementById("realForm").submit();
    }
  }

  loginForm.attachEvent("onButtonClick", function (id) {
    if (id == "login") {
      loginProcess();
    } else if (id == "cancel") {
      window.location.href = "index.php";
    }
  });

  loginForm.attachEvent("onEnter", function (id) {
    loginProcess();
  });
}

function submitCallback(status, jsonSession) {
  switch (status) {
    case "failed":
      loginForm.lock();
      if (!this.attempts) {
        this.attempts = 1;
      } else {
        this.attempts++;
      }

      setTimeout(function () {
        loginForm.unlock();
      }, this.attempts * 1000);

      dhtmlx.message({
        text: "Username atau Password salah<br>",
        type: "error",
        expire: 6000,
      });
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
      let sessionData = JSON.parse(jsonSession);

      userLogged = {
        userId: sessionData.userId,
        username: sessionData.username,
        roleId: sessionData.roleId,
        role: sessionData.role,
        empNip: sessionData.empNip,
        empId: sessionData.empId,
        empName: sessionData.empName,
        deptId: sessionData.deptId,
        department: sessionData.department,
        subId: sessionData.subId,
        subDepartment: sessionData.subDepartment,
        rankId: sessionData.rankId,
        rank: sessionData.rank,
        divId: sessionData.divId,
        division: sessionData.division,
        empLoc: sessionData.empLoc,
        locName: sessionData.locName,
        picOvertime: sessionData.picOvertime,
        pltDeptId: sessionData.pltDeptId,
        pltDepartment: sessionData.pltDepartment,
        pltSubId: sessionData.pltSubId,
        pltSubDepartment: sessionData.pltSubDepartment,
        pltDivId: sessionData.pltDivId,
        pltDivision: sessionData.pltDivision,
        pltRankId: sessionData.pltRankId,
      };

      localStorage.setItem("isLogin", true);

      loginForm.unload();
      $("#mainCanvas").removeClass("anim_gradient");
      $("#Container").remove();
      spinner = new Spinner().spin(document.body);
      loadExternalJS("layout");
      loadExternalJS("admin");
      loadPrivilageViewJS();
      break;
  }
}
