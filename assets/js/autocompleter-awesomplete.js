class Tools {}
/**
 *  Extend sans Jquery
 * https://gist.github.com/cferdinandi/4f8a0e17921c5b46e6c4
 * @param { } options
 */
Tools.prototype.extend = function (defaults, options) {
    let extended = {};
    let prop;
    for (prop in defaults) {
        if (Object.prototype.hasOwnProperty.call(defaults, prop)) {
            extended[prop] = defaults[prop];
        }
    }
    for (prop in options) {
        if (Object.prototype.hasOwnProperty.call(options, prop)) {
            extended[prop] = options[prop];
        }
    }
    return extended;
};

Tools.prototype.autocompleter = function (inputTarget, options) {
    let settings = {
        url_list: '',
        url_get: '',
        min_length: 2
    };

    if (options) {
        settings = this.extend(settings, options);
    }

    let inputTarget_fake = inputTarget.cloneNode(true);

    const regex = /\[.*\]/i;
    let nameOfInputTarget = inputTarget.getAttribute('name');
    let idOfInputisEdit = nameOfInputTarget;

    inputTarget_fake.setAttribute('id', 'fake_' + inputTarget.getAttribute('id'));
    inputTarget_fake.setAttribute('name', 'fake_' + inputTarget.getAttribute('name'));
    inputTarget.style.display = 'none';
    inputTarget.after(inputTarget_fake);

    if (document.querySelector(`#${
        idOfInputisEdit.replace(regex, '_isEdit')
    }`).value === 'true') {
        console.log("Is edit");

        fetch(`${
            settings.url_get
        }/${
            inputTarget.value
        }`).then((result) => {
            return result.text();
        }).then((data) => {
            inputTarget_fake.value = data;
        });
    }

    fetch(settings.url_list).then((result) => {
        return result.text();
    }).then((data) => {
        let list = JSON.parse(data).map(function (i) {
            return {label: i.label, value: i.id};
        });

        new Awesomplete(inputTarget_fake, {
            list: list,
            minChars: settings.min_length,
            // insert label instead of value into the input.
            replace: function (suggestion) {
                this.input.value = suggestion.label;
                inputTarget.value = suggestion.value;
            }
        });
    })
};

let tools = new Tools();
export default tools;
