let preview = {
  // default values
  defaults: {
    user: "",
    theme: "default",
    hide_border: "false",
  },
  // update the preview
  update: function () {
    // get parameter values from all .param elements
    const params = Array.from(document.querySelectorAll(".param")).reduce(
      (acc, next) => {
        let obj = { ...acc };
        obj[next.id] = next.value.replace(/#/g, "");
        return obj;
      },
      {}
    );
    // convert parameters to query string
    const encode = encodeURIComponent;
    const query = Object.keys(params)
      .filter((key) => params[key] && params[key] != this.defaults[key])
      .map((key) => encode(key) + "=" + encode(params[key]))
      .join("&");
    // generate links and markdown
    const imageURL = `${window.location.origin}?${query}`;
    const demoImageURL = `../test?${query}`;
    const repoLink =
      "https://github.com/DenverCoder1/github-readme-streak-stats";
    const md = `[![GitHub Streak](${imageURL})](${repoLink})`;
    // update image preview
    document.querySelector(".output img").src = demoImageURL;
    // update markdown
    document.querySelector(".md code").innerText = md;
  },
  addProperty: function (property) {
    const selectElement = document.querySelector("#properties");
    // if no property passed, get the currently selected property
    if (!property) {
      property = selectElement.value;
    }
    if (!selectElement.disabled) {
      // disable option in menu
      Array.prototype.find.call(
        selectElement.options,
        (o) => o.value == property
      ).disabled = true;
      // select first unselected option
      const firstAvailable = Array.prototype.find.call(
        selectElement.options,
        (o) => !o.disabled
      );
      if (firstAvailable) {
        firstAvailable.selected = true;
      } else {
        selectElement.disabled = true;
      }
      // label
      const label = document.createElement("label");
      label.innerText = property;
      label.setAttribute("data-property", property);
      // color picker
      const input = document.createElement("input");
      input.type = "color";
      input.className = "param";
      input.id = property;
      input.name = property;
      input.value = "#dd2727"; // default red color
      input.setAttribute("data-property", property);
      // removal button
      const minus = document.createElement("button");
      minus.className = "minus btn";
      minus.setAttribute(
        "onclick",
        "return preview.removeProperty(this.getAttribute('data-property'));"
      );
      minus.innerText = "−";
      minus.setAttribute("data-property", property);
      // add elements
      const parent = document.querySelector(".advanced .parameters");
      parent.appendChild(label);
      parent.appendChild(input);
      parent.appendChild(minus);
      // update and exit
      this.update();
    }
    return false;
  },
  removeProperty: function (property) {
    const parent = document.querySelector(".advanced .parameters");
    const selectElement = document.querySelector("#properties");
    // remove all elements for given property
    parent
      .querySelectorAll(`[data-property="${property}"]`)
      .forEach((x) => parent.removeChild(x));
    // enable option in menu
    const option = Array.prototype.find.call(
      selectElement.options,
      (o) => o.value == property
    );
    selectElement.disabled = false;
    option.disabled = false;
    // update and exit
    this.update();
    return false;
  },
};

let clipboard = {
  copy: function (el) {
    // create input box to copy from
    const input = document.createElement("input");
    input.value = document.querySelector(".md code").innerText;
    document.body.appendChild(input);
    // select all
    input.select();
    input.setSelectionRange(0, 99999);
    // copy
    document.execCommand("copy");
    // remove input box
    input.parentElement.removeChild(input);
    // set tooltip text
    el.title = "Copied!";
  },
};

let tooltip = {
  reset: function (el) {
    // remove tooltip text
    el.removeAttribute("title");
  },
};

// refresh preview on interactions with the page
document.addEventListener("keyup", () => preview.update(), false);
document.addEventListener("click", () => preview.update(), false);

// when the page loads
window.addEventListener(
  "load",
  () => {
    // set input boxes to match URL parameters
    new URLSearchParams(window.location.search).forEach((val, key) => {
      let paramInput = document.querySelector(`#${key}`);
      if (paramInput) {
        // set parameter value
        paramInput.value = val;
      } else {
        // add advanced property
        document.querySelector("details.advanced").open = true;
        preview.addProperty(key);
        paramInput = document.querySelector(`#${key}`);
        paramInput.value = val;
      }
    });
    // update previews
    preview.update();
  },
  false
);
