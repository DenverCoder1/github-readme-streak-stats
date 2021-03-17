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
        obj[next.id] = next.value;
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
    new URLSearchParams(window.location.search).forEach(
      (val, key) => (document.querySelector(`#${key}`).value = val)
    );
    // update previews
    preview.update();
  },
  false
);
