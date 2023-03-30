/*global jscolor*/
/*eslint no-undef: "error"*/

const preview = {
  /**
   * Default values - if set to these values, the params do not need to appear in the query string
   */
  defaults: {
    theme: "default",
    hide_border: "false",
    date_format: "",
    locale: "en",
    border_radius: "4.5",
    mode: "daily",
    type: "svg",
  },

  /**
   * Update the preview with the current parameters
   */
  update() {
    // get parameter values from all .param elements
    const params = this.objectFromElements(document.querySelectorAll(".param"));
    // convert parameters to query string
    const query = Object.keys(params)
      .filter((key) => params[key] !== this.defaults[key])
      .map((key) => {
        if (key === 'background') {
          return `${encodeURIComponent(key)}=${encodeURIComponent(params[key][0])},${encodeURIComponent(params[key][1])},${encodeURIComponent(params[key][2])}`
        }
        return `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`
      })
      .join("&");
    // generate links and markdown
    const imageURL = `${window.location.origin}?${query}`;
    const demoImageURL = `preview.php?${query}`;
    // update preview
    if (params.type !== "json") {
      const repoLink = "https://git.io/streak-stats";
      const md = `[![GitHub Streak](${imageURL})](${repoLink})`;
      document.querySelector(".output img").src = demoImageURL;
      document.querySelector(".md code").innerText = md;
      document.querySelector(".output img").style.display = "block";
      document.querySelector(".output .json").style.display = "none";
    } else {
      document.querySelector(".output img").style.display = "none";
      document.querySelector(".output .json").style.display = "block";
      fetch(demoImageURL)
        .then((response) => response.json())
        .then((data) => (document.querySelector(".output .json pre").innerText = JSON.stringify(data, null, 2)))
        .catch(console.error);
      document.querySelector(".md code").innerText = imageURL;
    }
    // disable copy button if username is invalid
    const copyButton = document.querySelector(".copy-button");
    copyButton.disabled = Boolean(document.querySelector("#user:invalid") || !document.querySelector("#user").value);
    // disable clear button if no added advanced options
    const clearButton = document.querySelector("#clear-button");
    clearButton.disabled = !document.querySelectorAll(".minus").length;
  },

  /**
   * Add a property in the advanced section
   * @param {string} property - the name of the property, selected element is used if not provided
   * @param {string} value - the value to set the property to
   */
  addProperty(property, value = "#EB5454FF") {
    const selectElement = document.querySelector("#properties");
    // select first unselected option
    const firstAvailable = Array.prototype.find.call(selectElement.options, (o) => !o.disabled);
    if (firstAvailable) {
      firstAvailable.selected = true;
    } else {
      selectElement.disabled = true;
    }
    // if no property passed, get the currently selected property
    const propertyName = property || selectElement.value;
    if (!selectElement.disabled) {
      // disable option in menu
      Array.prototype.find.call(selectElement.options, (o) => o.value === propertyName).disabled = true;
      // label
      const label = document.createElement("label");
      label.innerText = propertyName;
      label.setAttribute("data-property", propertyName);
      // color picker
      const jscolorConfig = {
        format: "hexa",
        onChange: `preview.pickerChange(this, '${propertyName}')`,
        onInput: `preview.pickerChange(this, '${propertyName}')`,
      };
      
      const parent = document.querySelector(".advanced .color-properties");
      if (propertyName === "background") {
        const input = document.createElement("span");
        input.className = "grid-middle";
        input.setAttribute("data-property", propertyName);

        const rotate = document.createElement("input");
        rotate.className = "param";
        rotate.type = "text";
        rotate.id = "rotate";
        rotate.placeholder = "0deg";
        rotate.value = "0deg";
        rotate.pattern = "^-[0-9]+deg|^[0-9]+[deg]+"

        const color1 = document.createElement("input");
        color1.className = "param jscolor";
        color1.id = "color1";
        color1.setAttribute("data-jscolor", JSON.stringify({
          format: "hexa",
          onChange: `preview.pickerChange(this, '${color1.id}')`,
          onInput: `preview.pickerChange(this, '${color1.id}')`,
        }));
        const color2 = document.createElement("input");
        color2.className = "param jscolor";
        color2.id = "color2";
        color2.setAttribute("data-jscolor", JSON.stringify({
          format: "hexa",
          onChange: `preview.pickerChange(this, '${color2.id}')`,
          onInput: `preview.pickerChange(this, '${color2.id}')`,
        }));
        rotate.name = color1.name = color2.name = propertyName;
        color1.value = color2.value = value;
        // add elements
        parent.appendChild(label);
        input.appendChild(rotate);
        input.appendChild(color1);
        input.appendChild(color2);
        parent.appendChild(input);
      } else {
        const input = document.createElement("input");
        input.className = "param jscolor";
        input.id = propertyName;
        input.name = propertyName;
        input.setAttribute("data-property", propertyName);
        input.setAttribute("data-jscolor", JSON.stringify(jscolorConfig));
        input.value = value;
        // add elements
        parent.appendChild(label);
        parent.appendChild(input);
      }
      // removal button
      const minus = document.createElement("button");
      minus.className = "minus btn";
      minus.setAttribute("onclick", "return preview.removeProperty(this.getAttribute('data-property'));");
      minus.setAttribute("type", "button");
      minus.innerText = "−";
      minus.setAttribute("data-property", propertyName);
      parent.appendChild(minus);
      
      // initialise jscolor on element
      jscolor.install(parent);

      // check initial color value
      this.checkColor(value, propertyName);

      // update and exit
      this.update();
    }
  },

  /**
   * Remove a property from the advanced section
   * @param {string} property - the name of the property to remove
   */
  removeProperty(property) {
    const parent = document.querySelector(".advanced .color-properties");
    const selectElement = document.querySelector("#properties");
    // remove all elements for given property
    parent.querySelectorAll(`[data-property="${property}"]`).forEach((x) => parent.removeChild(x));
    // enable option in menu
    const option = Array.prototype.find.call(selectElement.options, (o) => o.value === property);
    selectElement.disabled = false;
    option.disabled = false;
    // update and exit
    this.update();
  },

  /**
   * Removes all properties from the advanced section
   */
  removeAllProperties() {
    const parent = document.querySelector(".advanced .color-properties");
    const activeProperties = parent.querySelectorAll("[data-property]");
    // select active and unique property names
    const propertyNames = Array.prototype.map
      .call(activeProperties, (prop) => prop.getAttribute("data-property"))
      .filter((value, index, self) => self.indexOf(value) === index);
    // remove each active property name
    propertyNames.forEach((prop) => this.removeProperty(prop));
  },

  /**
   * Create a key-value mapping of names to values from all elements in a Node list
   * @param {NodeList} elements - the elements to get the values from
   * @returns {Object} the key-value mapping
   */
  objectFromElements(elements) {
    let mCount = 0;
    return Array.from(elements).reduce((acc, next) => {
      const obj = { ...acc };
      if (obj.background !== undefined) {
        mCount++;
      } else if (mCount >= 3) mCount = 0;
      let value = next.value;
      if (value.indexOf("#") >= 0) {
        // if the value is colour, remove the hash sign
        value = value.replace(/#/g, "");
        if (value.length > 6) {
          // if the value is in hexa and opacity is 1, remove FF
          value = value.replace(/[Ff]{2}$/, "");
        }
      } else if (value.indexOf("deg") >= 0) {
        value = value.replace(/deg/g, "");
      }
      if (mCount <= 0)
        obj[next.name] = [];
      obj[next.name].push(value);
      return obj;
    }, {});
  },

  /**
   * Export the advanced parameters to PHP code for creating a new theme
   */
  exportPhp() {
    // get default values from the currently selected theme
    const themeSelect = document.querySelector("#theme");
    const selectedOption = themeSelect.options[themeSelect.selectedIndex];
    const defaultParams = selectedOption.dataset;
    // get parameters with the advanced options
    const advancedParams = this.objectFromElements(document.querySelectorAll(".advanced .param.jscolor"));
    // update default values with the advanced options
    const params = { ...defaultParams, ...advancedParams };
    // convert parameters to PHP code
    const mappings = Object.keys(params)
      .map((key) => `  "${key}" => "#${params[key]}",`)
      .join("\n");
    const output = `[\n${mappings}\n]`;
    // set the textarea value to the output
    const textarea = document.getElementById("exported-php");
    textarea.value = output;
    textarea.hidden = false;
  },

  /**
   * Remove "FF" from a hex color if opacity is 1
   * @param {string} color - the hex color
   * @param {string} input - the property name, or id of the element to update
   * @param {boolean} setColor - if true set the color to the input else update original value
   */
  checkColor(color, input, setColor = false) {
    // if color has hex alpha value -> remove it
    if (color.length === 9 && color.slice(-2) === "FF") {
      for (const el of document.querySelectorAll(`[name="${input}"]`))
        if (el.value.length === 9 && color.slice(-2) === "FF")
          el.value = setColor ? color.slice(0, -2) : el.value.slice(0, -2);
      }
  },

  /**
   * Check a color when the picker changes
   * @param {Object} picker - the JSColor picker object
   * @param {string} input - the property name, or id of the element to update
   */
  pickerChange(picker, input) {
    // color was changed by picker - check it
    this.checkColor(picker.toHEXAString(), input, true);
    // update preview
    this.update();
  },
};

const clipboard = {
  /**
   * Copy the content of an element to the clipboard
   * @param {Element} el - the element to copy
   */
  copy(el) {
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

const tooltip = {
  /**
   * Reset the tooltip text
   * @param {Element} el - the element to reset the tooltip for
   */
  reset(el) {
    // remove tooltip text
    el.removeAttribute("title");
  },
};

// when the page loads
window.addEventListener(
  "load",
  () => {
    // refresh preview on interactions with the page
    const refresh = () => preview.update();
    document.addEventListener("keyup", refresh, false);
    [...document.querySelectorAll("select:not(#properties)")].forEach((element) => {
      element.addEventListener("change", refresh, false);
    });
    // set input boxes to match URL parameters
    new URLSearchParams(window.location.search).forEach((val, key) => {
      const paramInput = document.querySelector(`[name="${key}"]`);
      if (paramInput) {
        // set parameter value
        paramInput.value = val;
      } else {
        // add advanced property
        document.querySelector("details.advanced").open = true;
        preview.addProperty(key, val);
      }
    });
    // update previews
    preview.update();
  },
  false
);
