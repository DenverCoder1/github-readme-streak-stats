if (
  getCookie("darkmode") === null &&
  window.matchMedia("(prefers-color-scheme: dark)").matches == true
) {
  darkmode();
}

function toggleTheme() {
  if (document.body.getAttribute("data-theme") !== "dark") {
    /* dark mode on */
    darkmode();
  } else {
    /* dark mode off */
    lightmode();
  }
}

function darkmode() {
  document.querySelector(".darkmode i").className = "gg-sun";
  setCookie("darkmode", "on", 9999);
  document.body.setAttribute("data-theme", "dark");
}

function lightmode() {
  document.querySelector(".darkmode i").className = "gg-moon";
  setCookie("darkmode", "off", 9999);
  document.body.removeAttribute("data-theme");
}
function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(name) {
  var dc = document.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1) {
    begin = dc.indexOf(prefix);
    if (begin != 0) return null;
  } else {
    begin += 2;
    var end = document.cookie.indexOf(";", begin);
    if (end == -1) {
      end = dc.length;
    }
  }
  return decodeURI(dc.substring(begin + prefix.length, end));
}
