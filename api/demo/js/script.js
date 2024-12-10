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
    exclude_days: "",
    card_width: "495",
    card_height: "195",
    hide_total_contributions: "false",
    hide_current_streak: "false",
    hide_longest_streak: "false",
    short_numbers: "false",
  },

  /**
   * Update the preview with the current parameters
   */
  update() {
    // get parameter values from all .param elements
    const params = this.objectFromElements(document.querySelectorAll(".param"));
    // convert sections to hide_... parameters
    params.hide_total_contributions = String(!params.sections.includes("total"));
    params.hide_current_streak = String(!params.sections.includes("current"));
    params.hide_longest_streak = String(!params.sections.includes("longest"));
    delete params.sections;
    // convert parameters to query string
    const query = Object.keys(params)
      .filter((key) => params[key] !== this.defaults[key])
      .map((key) => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`)
      .join("&");
    // generate links and markdown
    const imageURL = `${window.location.origin}?${query}`;
    const demoImageURL = `preview.php?${query}`;
    // update preview
    if (params.type !== "json") {
      const repoLink = "https://git.io/streak-stats";
      const md = `[![GitHub Streak](${imageURL})](${repoLink})`;
      const html = `<a href="${repoLink}"><img src="${imageURL}" alt="GitHub Streak" /></a>`;
      document.querySelector(".output img").src = demoImageURL;
      document.querySelector(".md code").innerText = md;
      document.querySelector(".html code").innerText = html;
      document.querySelector(".copy-md").parentElement.style.display = "block";
      document.querySelector(".copy-html").parentElement.style.display = "block";
      document.querySelector(".output img").style.display = "block";
      document.querySelector(".output .json").style.display = "none";
      document.querySelector(".copy-json").parentElement.style.display = "none";
    } else {
      fetch(demoImageURL)
        .then((response) => response.json())
        .then((data) => (document.querySelector(".output .json pre").innerText = JSON.stringify(data, null, 2)))
        .catch(console.error);
      document.querySelector(".json code").innerText = imageURL;
      document.querySelector(".copy-md").parentElement.style.display = "none";
      document.querySelector(".copy-html").parentElement.style.display = "none";
      document.querySelector(".output img").style.display = "none";
      document.querySelector(".output .json").style.display = "block";
      document.querySelector(".copy-json").parentElement.style.display = "block";
    }
    // disable copy button if username is invalid
    const copyButtons = document.querySelectorAll(".copy-button");
    copyButtons.forEach((button) => {
      button.disabled = Boolean(document.querySelector("#user:invalid") || !document.querySelector("#user").value);
    });
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
    // if no property passed, get the currently selected property
    const propertyName = property || selectElement.value;
    if (!selectElement.disabled) {
      // disable option in menu
      Array.prototype.find.call(selectElement.options, (o) => o.value === propertyName).disabled = true;
      // select first unselected option
      const firstAvailable = Array.prototype.find.call(selectElement.options, (o) => !o.disabled);
      if (firstAvailable) {
        firstAvailable.selected = true;
      } else {
        selectElement.disabled = true;
      }
      // color picker
      const jscolorConfig = {
        format: "hexa",
        onChange: `preview.pickerChange(this, '${propertyName}')`,
        onInput: `preview.pickerChange(this, '${propertyName}')`,
      };

      const parent = document.querySelector(".advanced .color-properties");
      if (propertyName === "background" && document.querySelector("#background-type-gradient").checked) {
        const valueParts = value.split(",");
        let angleValue = "45";
        let color1Value = "#EB5454FF";
        let color2Value = "#EB5454FF";
        if (valueParts.length === 3) {
          angleValue = valueParts[0];
          color1Value = valueParts[1];
          color2Value = valueParts[2];
        }

        const input = document.createElement("span");
        input.className = "grid-middle";
        input.setAttribute("data-property", propertyName);

        const rotateInputGroup = document.createElement("div");
        rotateInputGroup.className = "input-text-group";

        const rotate = document.createElement("input");
        rotate.className = "param";
        rotate.type = "number";
        rotate.id = "rotate";
        rotate.placeholder = "45";
        rotate.value = angleValue;

        const degText = document.createElement("span");
        degText.innerText = "\u00B0"; // degree symbol

        rotateInputGroup.appendChild(rotate);
        rotateInputGroup.appendChild(degText);

        const color1 = document.createElement("input");
        color1.className = "param jscolor";
        color1.id = "background-color1";
        color1.setAttribute(
          "data-jscolor",
          JSON.stringify({
            format: "hexa",
            onChange: `preview.pickerChange(this, '${color1.id}')`,
            onInput: `preview.pickerChange(this, '${color1.id}')`,
          })
        );
        const color2 = document.createElement("input");
        color2.className = "param jscolor";
        color2.id = "background-color2";
        color2.setAttribute(
          "data-jscolor",
          JSON.stringify({
            format: "hexa",
            onChange: `preview.pickerChange(this, '${color2.id}')`,
            onInput: `preview.pickerChange(this, '${color2.id}')`,
          })
        );
        rotate.name = color1.name = color2.name = propertyName;
        color1.value = color1Value;
        color2.value = color2Value;
        // label
        const label = document.createElement("span");
        label.innerText = propertyName;
        label.setAttribute("data-property", propertyName);
        label.id = "background-label";
        input.setAttribute("role", "group");
        input.setAttribute("aria-labelledby", "background-label");
        // add elements
        parent.appendChild(label);
        input.appendChild(rotateInputGroup);
        input.appendChild(color1);
        input.appendChild(color2);
        parent.appendChild(input);
        // initialise jscolor on elements
        jscolor.install(input);
        // check initial color values
        this.checkColor(color1.value, color1.id);
        this.checkColor(color2.value, color2.id);
      } else {
        const input = document.createElement("input");
        input.className = "param jscolor";
        input.id = propertyName;
        input.name = propertyName;
        input.setAttribute("data-property", propertyName);
        input.setAttribute("data-jscolor", JSON.stringify(jscolorConfig));
        input.value = value;
        // label
        const label = document.createElement("label");
        label.innerText = propertyName;
        label.setAttribute("data-property", propertyName);
        label.setAttribute("for", propertyName);
        // add elements
        parent.appendChild(label);
        parent.appendChild(input);
        // initialise jscolor on element
        jscolor.install(parent);
        // check initial color value
        this.checkColor(value, propertyName);
      }
      // removal button
      const minus = document.createElement("button");
      minus.className = "minus btn";
      minus.setAttribute("onclick", "return preview.removeProperty(this.getAttribute('data-property'));");
      minus.setAttribute("type", "button");
      minus.innerText = "−";
      minus.setAttribute("data-property", propertyName);
      parent.appendChild(minus);

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
    selectElement.value = option.value;
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
    return Array.from(elements).reduce((acc, next) => {
      const obj = { ...acc };
      let value = next.value;
      if (value.indexOf("#") >= 0) {
        // if the value is colour, remove the hash sign
        value = value.replace(/#/g, "");
        if (value.length > 6) {
          // if the value is in hexa and opacity is 1, remove FF
          value = value.replace(/[Ff]{2}$/, "");
        }
      }
      // if the property already exists, append the value to the existing one
      if (next.name in obj) {
        obj[next.name] = `${obj[next.name]},${value}`;
        return obj;
      }
      // otherwise, add the value to the object
      obj[next.name] = value;
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
    const advancedParams = this.objectFromElements(document.querySelectorAll(".advanced .param"));
    // update default values with the advanced options
    const params = { ...defaultParams, ...advancedParams };
    // convert parameters to PHP code
    const mappings = Object.keys(params)
      .map((key) => {
        const value = params[key].includes(",") ? params[key] : `#${params[key]}`;
        return `  "${key}" => "${value}",`;
      })
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
   */
  checkColor(color, input) {
    // if color has hex alpha value -> remove it
    if (color.length === 9 && color.slice(-2) === "FF") {
      document.querySelector(`#${input}`).value = color.slice(0, -2);
    }
  },

  /**
   * Check a color when the picker changes
   * @param {Object} picker - the JSColor picker object
   * @param {string} input - the property name, or id of the element to update
   */
  pickerChange(picker, input) {
    // color was changed by picker - check it
    this.checkColor(picker.toHEXAString(), input);
    // update preview
    this.update();
  },

  /**
   * Update checkboxes based on the query string parameter
   *
   * @param {string|null} param - the query string parameter to read
   * @param {string} selector - the selector of the parent container to find the checkboxes
   */
  updateCheckboxes(param, selector) {
    if (!param) {
      return;
    }
    // uncheck all checkboxes
    [...document.querySelectorAll(`${selector} input[value]`)].forEach((checkbox) => {
      checkbox.checked = false;
    });
    // check checkboxes based on values in the query string
    param.split(",").forEach((value) => {
      const checkbox = document.querySelector(`${selector} input[value="${value}"]`);
      if (checkbox) {
        checkbox.checked = true;
      }
    });
  },

  /**
   * Assign values to input boxes based on the query string
   *
   * @param {URLSearchParams} searchParams - the query string parameters or empty to use the current URL
   */
  updateFormInputs(searchParams) {
    searchParams = searchParams || new URLSearchParams(window.location.search);
    const backgroundParams = searchParams.getAll("background");
    // set background-type
    if (backgroundParams.length > 1) {
      document.querySelector("#background-type-gradient").checked = true;
    }
    // set input field and select values
    searchParams.forEach((val, key) => {
      const paramInput = document.querySelector(`[name="${key}"]`);
      if (paramInput) {
        // set parameter value
        paramInput.value = val;
      } else {
        // add advanced property
        document.querySelector("details.advanced").open = true;
        preview.addProperty(key, searchParams.getAll(key).join(","));
      }
    });
    // set background angle and gradient colors
    if (backgroundParams.length > 1) {
      document.querySelector("#rotate").value = backgroundParams[0];
      document.querySelector("#background-color1").value = backgroundParams[1];
      document.querySelector("#background-color2").value = backgroundParams[2];
      preview.checkColor(backgroundParams[1], "background-color1");
      preview.checkColor(backgroundParams[2], "background-color2");
    }
    // set weekday checkboxes
    this.updateCheckboxes(searchParams.get("exclude_days"), ".weekdays");
    // set show sections checkboxes
    this.updateCheckboxes(searchParams.get("sections"), ".sections");
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
    if (el.classList.contains("copy-md")) {
      input.value = document.querySelector(".md code").innerText;
    } else if (el.classList.contains("copy-html")) {
      input.value = document.querySelector(".html code").innerText;
    } else if (el.classList.contains("copy-json")) {
      input.value = document.querySelector(".json code").innerText;
    }
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
    // when the background-type changes, remove the background and replace it
    const toggleBackgroundType = () => {
      const value = document.querySelector("input#background, input#background-color1")?.value;
      preview.removeProperty("background");
      if (value && document.querySelector("#background-type-gradient").checked) {
        preview.addProperty("background", `45,${value},${value}`);
      } else if (value) {
        preview.addProperty("background", value);
      }
    };
    document.querySelector("#background-type-solid").addEventListener("change", toggleBackgroundType, false);
    document.querySelector("#background-type-gradient").addEventListener("change", toggleBackgroundType, false);
    // function to update the hidden input box when checkboxes are clicked
    const updateCheckboxTextField = (parentSelector, inputSelector) => {
      const checked = document.querySelectorAll(`${parentSelector} input:checked`);
      document.querySelector(inputSelector).value = [...checked].map((node) => node.value).join(",");
      preview.update();
    };
    // when weekdays are toggled, update the input field
    document.querySelectorAll(".weekdays input[type='checkbox']").forEach((el) => {
      el.addEventListener("click", () => {
        updateCheckboxTextField(".weekdays", "#exclude-days");
      });
    });
    // when sections are toggled, update the input field
    document.querySelectorAll(".sections input[type='checkbox']").forEach((el) => {
      el.addEventListener("click", () => {
        updateCheckboxTextField(".sections", "#sections");
      });
    });
    // when mode is set to "weekly", disable checkboxes, otherwise enable them
    const toggleExcludedDaysCheckboxes = () => {
      const mode = document.querySelector("#mode").value;
      document.querySelectorAll(".weekdays input[type='checkbox']").forEach((el) => {
        const labelEl = el.nextElementSibling;
        if (mode === "weekly") {
          el.disabled = true;
          labelEl.title = "Disabled in weekly mode";
        } else {
          el.disabled = false;
          labelEl.title = labelEl.dataset.tooltip;
        }
      });
    };
    document.querySelector("#mode").addEventListener("change", toggleExcludedDaysCheckboxes, false);
    // set input boxes to match URL parameters
    preview.updateFormInputs();
    toggleExcludedDaysCheckboxes();
    // update previews
    preview.update();
  },
  false
);
