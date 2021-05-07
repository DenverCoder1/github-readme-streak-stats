const icon = document.querySelector(".darkmode i");
if (window.matchMedia("(prefers-color-scheme: dark)").matches == true) {
  darkmode();
} else {
  lightmode();
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
  icon.className = "gg-sun";
  setCookie("darkmode", "on", 9999);
  document.body.setAttribute("data-theme", "dark");
}

function lightmode() {
  icon.className = "gg-moon";
  setCookie("darkmode", "off", 9999);
  document.body.removeAttribute("data-theme");
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
