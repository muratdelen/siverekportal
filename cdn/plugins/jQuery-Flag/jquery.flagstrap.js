(function ($) {

    var defaults = {
        buttonSize: "btn-md",
        buttonType: "btn-default",
        labelMargin: "10px",
        scrollable: true,
        scrollableHeight: "250px"
    };
    var countries = {
        "AF": "Afghanistan",
        "AL": "Albania",
        "DZ": "Algeria",
        "AS": "American Samoa",
        "AD": "Andorra",
        "AO": "Angola",
        "AI": "Anguilla",
        "AG": "Antigua and Barbuda",
        "AR": "Argentina",
        "AM": "Armenia",
        "AW": "Aruba",
        "AU": "Australia",
        "AT": "Austria",
        "AZ": "Azerbaijan",
        "BS": "Bahamas",
        "BH": "Bahrain",
        "BD": "Bangladesh",
        "BB": "Barbados",
        "BY": "Belarus",
        "BE": "Belgium",
        "BZ": "Belize",
        "BJ": "Benin",
        "BM": "Bermuda",
        "BT": "Bhutan",
        "BO": "Bolivia, Plurinational State of",
        "BA": "Bosnia and Herzegovina",
        "BW": "Botswana",
        "BV": "Bouvet Island",
        "BR": "Brazil",
        "IO": "British Indian Ocean Territory",
        "BN": "Brunei Darussalam",
        "BG": "Bulgaria",
        "BF": "Burkina Faso",
        "BI": "Burundi",
        "KH": "Cambodia",
        "CM": "Cameroon",
        "CA": "Canada",
        "CV": "Cape Verde",
        "KY": "Cayman Islands",
        "CF": "Central African Republic",
        "TD": "Chad",
        "CL": "Chile",
        "CN": "China",
        "CO": "Colombia",
        "KM": "Comoros",
        "CG": "Congo",
        "CD": "Congo, the Democratic Republic of the",
        "CK": "Cook Islands",
        "CR": "Costa Rica",
        "CI": "CÃ´te d'Ivoire",
        "HR": "Croatia",
        "CU": "Cuba",
        "CW": "CuraÃ§ao",
        "CY": "Cyprus",
        "CZ": "Czech Republic",
        "DK": "Denmark",
        "DJ": "Djibouti",
        "DM": "Dominica",
        "DO": "Dominican Republic",
        "EC": "Ecuador",
        "EG": "Egypt",
        "SV": "El Salvador",
        "GQ": "Equatorial Guinea",
        "ER": "Eritrea",
        "EE": "Estonia",
        "ET": "Ethiopia",
        "FK": "Falkland Islands (Malvinas)",
        "FO": "Faroe Islands",
        "FJ": "Fiji",
        "FI": "Finland",
        "FR": "France",
        "GF": "French Guiana",
        "PF": "French Polynesia",
        "TF": "French Southern Territories",
        "GA": "Gabon",
        "GM": "Gambia",
        "GE": "Georgia",
        "DE": "Germany",
        "GH": "Ghana",
        "GI": "Gibraltar",
        "GR": "Greece",
        "GL": "Greenland",
        "GD": "Grenada",
        "GP": "Guadeloupe",
        "GU": "Guam",
        "GT": "Guatemala",
        "GG": "Guernsey",
        "GN": "Guinea",
        "GW": "Guinea-Bissau",
        "GY": "Guyana",
        "HT": "Haiti",
        "HM": "Heard Island and McDonald Islands",
        "VA": "Holy See (Vatican City State)",
        "HN": "Honduras",
        "HK": "Hong Kong",
        "HU": "Hungary",
        "IS": "Iceland",
        "IN": "India",
        "ID": "Indonesia",
        "IR": "Iran, Islamic Republic of",
        "IQ": "Iraq",
        "IE": "Ireland",
        "IM": "Isle of Man",
        "IL": "Israel",
        "IT": "Italy",
        "JM": "Jamaica",
        "JP": "Japan",
        "JE": "Jersey",
        "JO": "Jordan",
        "KZ": "Kazakhstan",
        "KE": "Kenya",
        "KI": "Kiribati",
        "KP": "Korea, Democratic People's Republic of",
        "KR": "Korea, Republic of",
        "KU": "Kurdish",
        "KW": "Kuwait",
        "KG": "Kyrgyzstan",
        "LA": "Lao People's Democratic Republic",
        "LV": "Latvia",
        "LB": "Lebanon",
        "LS": "Lesotho",
        "LR": "Liberia",
        "LY": "Libya",
        "LI": "Liechtenstein",
        "LT": "Lithuania",
        "LU": "Luxembourg",
        "MO": "Macao",
        "MK": "Macedonia, the former Yugoslav Republic of",
        "MG": "Madagascar",
        "MW": "Malawi",
        "MY": "Malaysia",
        "MV": "Maldives",
        "ML": "Mali",
        "MT": "Malta",
        "MH": "Marshall Islands",
        "MQ": "Martinique",
        "MR": "Mauritania",
        "MU": "Mauritius",
        "YT": "Mayotte",
        "MX": "Mexico",
        "FM": "Micronesia, Federated States of",
        "MD": "Moldova, Republic of",
        "MC": "Monaco",
        "MN": "Mongolia",
        "ME": "Montenegro",
        "MS": "Montserrat",
        "MA": "Morocco",
        "MZ": "Mozambique",
        "MM": "Myanmar",
        "NA": "Namibia",
        "NR": "Nauru",
        "NP": "Nepal",
        "NL": "Netherlands",
        "NC": "New Caledonia",
        "NZ": "New Zealand",
        "NI": "Nicaragua",
        "NE": "Niger",
        "NG": "Nigeria",
        "NU": "Niue",
        "NF": "Norfolk Island",
        "MP": "Northern Mariana Islands",
        "NO": "Norway",
        "OM": "Oman",
        "PK": "Pakistan",
        "PW": "Palau",
        "PS": "Palestinian Territory, Occupied",
        "PA": "Panama",
        "PG": "Papua New Guinea",
        "PY": "Paraguay",
        "PE": "Peru",
        "PH": "Philippines",
        "PN": "Pitcairn",
        "PL": "Poland",
        "PT": "Portugal",
        "PR": "Puerto Rico",
        "QA": "Qatar",
        "RE": "RÃ©union",
        "RO": "Romania",
        "RU": "Russian Federation",
        "RW": "Rwanda",
        "SH": "Saint Helena, Ascension and Tristan da Cunha",
        "KN": "Saint Kitts and Nevis",
        "LC": "Saint Lucia",
        "MF": "Saint Martin (French part)",
        "PM": "Saint Pierre and Miquelon",
        "VC": "Saint Vincent and the Grenadines",
        "WS": "Samoa",
        "SM": "San Marino",
        "ST": "Sao Tome and Principe",
        "SA": "Saudi Arabia",
        "SN": "Senegal",
        "RS": "Serbia",
        "SC": "Seychelles",
        "SL": "Sierra Leone",
        "SG": "Singapore",
        "SX": "Sint Maarten (Dutch part)",
        "SK": "Slovakia",
        "SI": "Slovenia",
        "SB": "Solomon Islands",
        "SO": "Somalia",
        "ZA": "South Africa",
        "GS": "South Georgia and the South Sandwich Islands",
        "SS": "South Sudan",
        "ES": "Spain",
        "LK": "Sri Lanka",
        "SD": "Sudan",
        "SR": "Suriname",
        "SZ": "Swaziland",
        "SE": "Sweden",
        "CH": "Switzerland",
        "SY": "Syrian Arab Republic",
        "TW": "Taiwan, Province of China",
        "TJ": "Tajikistan",
        "TZ": "Tanzania, United Republic of",
        "TH": "Thailand",
        "TL": "Timor-Leste",
        "TG": "Togo",
        "TK": "Tokelau",
        "TO": "Tonga",
        "TT": "Trinidad and Tobago",
        "TN": "Tunisia",
        "TR": "Turkey",
        "TM": "Turkmenistan",
        "TC": "Turks and Caicos Islands",
        "TV": "Tuvalu",
        "UG": "Uganda",
        "UA": "Ukraine",
        "AE": "United Arab Emirates",
        "GB": "United Kingdom",
        "US": "United States",
        "UM": "United States Minor Outlying Islands",
        "UY": "Uruguay",
        "UZ": "Uzbekistan",
        "VU": "Vanuatu",
        "VE": "Venezuela, Bolivarian Republic of",
        "VN": "Viet Nam",
        "VG": "Virgin Islands, British",
        "VI": "Virgin Islands, U.S.",
        "WF": "Wallis and Futuna",
        "EH": "Western Sahara",
        "YE": "Yemen",
        "ZM": "Zambia",
        "ZW": "Zimbabwe"
    };
//    http://www.lingoes.net/en/translator/langcode.htm
    var countries_language = {
        "AF": "fa",
        "AL": "sq",
        "DZ": "ar",
        "AS": "American Samoa",
        "AD": "Andorra",
        "AO": "Angola",
        "AI": "Anguilla",
        "AG": "Antigua and Barbuda",
        "AR": "es",
        "AM": "hy",
        "AW": "Aruba",
        "AU": "en",
        "AT": "de",
        "AZ": "az",
        "BS": "Bahamas",
        "BH": "ar",
        "BD": "Bangladesh",
        "BB": "Barbados",
        "BY": "be",
        "BE": "fr",
        "BZ": "en",
        "BJ": "Benin",
        "BM": "Bermuda",
        "BT": "Bhutan",
        "BO": "Bolivia, Plurinational State of",
        "BA": "Bosnia and Herzegovina",
        "BW": "Botswana",
        "BV": "Bouvet Island",
        "BR": "pt",
        "IO": "British Indian Ocean Territory",
        "BN": "ms",
        "BG": "bg",
        "BF": "Burkina Faso",
        "BI": "Burundi",
        "KH": "Cambodia",
        "CM": "Cameroon",
        "CA": "en",
        "CV": "Cape Verde",
        "KY": "Cayman Islands",
        "CF": "Central African Republic",
        "TD": "Chad",
        "CL": "es",
        "CN": "zh",
        "CO": "es",
        "KM": "Comoros",
        "CG": "Congo",
        "CD": "Congo, the Democratic Republic of the",
        "CK": "Cook Islands",
        "CR": "es",
        "CI": "CÃ´te d'Ivoire",
        "HR": "hr",
        "CU": "Cuba",
        "CW": "CuraÃ§ao",
        "CY": "Cyprus",
        "CZ": "cs",
        "DK": "da",
        "DJ": "Djibouti",
        "DM": "Dominica",
        "DO": "es",
        "EC": "qu",
        "EG": "ar",
        "SV": "es",
        "GQ": "Equatorial Guinea",
        "ER": "Eritrea",
        "EE": "et",
        "ET": "Ethiopia",
        "FK": "Falkland Islands (Malvinas)",
        "FO": "fo",
        "FJ": "Fiji",
        "FI": "fi",
        "FR": "fr",
        "GF": "French Guiana",
        "PF": "French Polynesia",
        "TF": "French Southern Territories",
        "GA": "Gabon",
        "GM": "Gambia",
        "GE": "ka",
        "DE": "de",
        "GH": "Ghana",
        "GI": "Gibraltar",
        "GR": "el",
        "GL": "Greenland",
        "GD": "Grenada",
        "GP": "Guadeloupe",
        "GU": "Guam",
        "GT": "es",
        "GG": "Guernsey",
        "GN": "Guinea",
        "GW": "Guinea-Bissau",
        "GY": "Guyana",
        "HT": "Haiti",
        "HM": "Heard Island and McDonald Islands",
        "VA": "Holy See (Vatican City State)",
        "HN": "es",
        "HK": "zh",
        "HU": "hu",
        "IS": "is",
        "IN": "kn",
        "ID": "id",
        "IR": "fa",
        "IQ": "ar",
        "IE": "en",
        "IM": "Isle of Man",
        "IL": "he",
        "IT": "it",
        "JM": "en",
        "JP": "ja",
        "JE": "Jersey",
        "JO": "ar",
        "KZ": "kk",
        "KE": "sw",
        "KI": "Kiribati",
        "KP": "Korea, Democratic People's Republic of",
        "KR": "Korea, Republic of",
        "KU": "ku",
        "KW": "ar",
        "KG": "ky",
        "LA": "Lao People's Democratic Republic",
        "LV": "lv",
        "LB": "ar",
        "LS": "Lesotho",
        "LR": "Liberia",
        "LY": "ar",
        "LI": "de",
        "LT": "lt",
        "LU": "de",
        "MO": "Macao",
        "MK": "mk",
        "MG": "Madagascar",
        "MW": "Malawi",
        "MY": "ms",
        "MV": "dv",
        "ML": "Mali",
        "MT": "Malta",
        "MH": "Marshall Islands",
        "MQ": "Martinique",
        "MR": "Mauritania",
        "MU": "Mauritius",
        "YT": "Mayotte",
        "MX": "es",
        "FM": "Micronesia, Federated States of",
        "MD": "Moldova, Republic of",
        "MC": "Monaco",
        "MN": "mn",
        "ME": "Montenegro",
        "MS": "Montserrat",
        "MA": "ar",
        "MZ": "Mozambique",
        "MM": "Myanmar",
        "NA": "Namibia",
        "NR": "Nauru",
        "NP": "Nepal",
        "NL": "nl",
        "NC": "New Caledonia",
        "NZ": "en",
        "NI": "es",
        "NE": "Niger",
        "NG": "Nigeria",
        "NU": "Niue",
        "NF": "Norfolk Island",
        "MP": "Northern Mariana Islands",
        "NO": "se",
        "OM": "ar",
        "PK": "ur",
        "PW": "Palau",
        "PS": "Palestinian Territory, Occupied",
        "PA": "es",
        "PG": "Papua New Guinea",
        "PY": "es",
        "PE": "Peru",
        "PH": "tl",
        "PN": "Pitcairn",
        "PL": "pl",
        "PT": "pt",
        "PR": "es",
        "QA": "ar",
        "RE": "RÃ©union",
        "RO": "ro",
        "RU": "ru",
        "RW": "Rwanda",
        "SH": "Saint Helena, Ascension and Tristan da Cunha",
        "KN": "Saint Kitts and Nevis",
        "LC": "Saint Lucia",
        "MF": "Saint Martin (French part)",
        "PM": "Saint Pierre and Miquelon",
        "VC": "Saint Vincent and the Grenadines",
        "WS": "Samoa",
        "SM": "San Marino",
        "ST": "Sao Tome and Principe",
        "SA": "ar",
        "SN": "Senegal",
        "RS": "sr",
        "SC": "Seychelles",
        "SL": "Sierra Leone",
        "SG": "zh",
        "SX": "Sint Maarten (Dutch part)",
        "SK": "sk",
        "SI": "sl",
        "SB": "Solomon Islands",
        "SO": "Somalia",
        "ZA": "af",
        "GS": "South Georgia and the South Sandwich Islands",
        "SS": "South Sudan",
        "ES": "ca",
        "LK": "Sri Lanka",
        "SD": "Sudan",
        "SR": "Suriname",
        "SZ": "Swaziland",
        "SE": "se",
        "CH": "de",
        "SY": "ar",
        "TW": "Taiwan, Province of China",
        "TJ": "Tajikistan",
        "TZ": "Tanzania, United Republic of",
        "TH": "th",
        "TL": "Timor-Leste",
        "TG": "Togo",
        "TK": "Tokelau",
        "TO": "Tonga",
        "TT": "en",
        "TN": "ar",
        "TR": "tr",
        "TM": "Turkmenistan",
        "TC": "Turks and Caicos Islands",
        "TV": "Tuvalu",
        "UG": "Uganda",
        "UA": "uk",
        "AE": "ar",
        "GB": "en",
        "US": "en",
        "UM": "United States Minor Outlying Islands",
        "UY": "es",
        "UZ": "uz",
        "VU": "Vanuatu",
        "VE": "es, Bolivarian Republic of",
        "VN": "vi",
        "VG": "Virgin Islands, British",
        "VI": "Virgin Islands, U.S.",
        "WF": "Wallis and Futuna",
        "EH": "Western Sahara",
        "YE": "ar",
        "ZM": "Zambia",
        "ZW": "en"
    };
    $.flagStrap = function (element, options, i) {

        var plugin = this;
        var uniqueId = generateId(8);
        plugin.countries = {};
        plugin.selected = {value: null, text: null};
        plugin.settings = {inputName: 'country-' + uniqueId};
        var $container = $(element);
        var htmlSelectId = 'flagstrap-' + uniqueId;
        var htmlSelect = '#' + htmlSelectId;
        var selected_language = "";
        plugin.init = function () {

            // Merge in global settings then merge in individual settings via data attributes
            plugin.countries = countries;
            // Initialize Settings, priority: defaults, init options, data attributes
            plugin.countries = countries;
            plugin.settings = $.extend({}, defaults, options, $container.data());
            if (undefined !== plugin.settings.countries) {
                plugin.countries = plugin.settings.countries;
            }

            if (undefined !== plugin.settings.inputId) {
                htmlSelectId = plugin.settings.inputId;
                htmlSelect = '#' + htmlSelectId;
            }

            // Build HTML Select, Construct the drop down button, Assemble the drop down list items element and insert
            $container
                    .addClass('flagstrap')
                    .append(buildHtmlSelect)
//                    .append(buildSearchText) //Burası Değiştirildi.
                    .append(buildDropDownButton)
                    .append(buildDropDownButtonItemList)
                    .append('<input type="hidden" id = "' + plugin.settings.inputName + '-language" name = "' + plugin.settings.inputName + '-language" value="' + selected_language + '" ></input> ');
            // Hide the actual HTML select
            $(htmlSelect).hide();
            $("#flagstrap-drop-down-" + uniqueId).focus(function () {
                $("#flagstrap-search-country-" + uniqueId).focus();
//             $("#flagstrap-search-country-" + uniqueId).val("");
            });
        };
        var buildHtmlSelect = function () {
            var htmlSelectElement = $('<select/>').attr('id', htmlSelectId).attr('name', plugin.settings.inputName);
            $.each(plugin.countries, function (code, country) {
                var optionAttributes = {value: code};
                if (plugin.settings.selectedCountry !== undefined) {
                    if (plugin.settings.selectedCountry === code) {
                        optionAttributes = {value: code, selected: "selected"};
                        plugin.selected = {value: code, text: country}
                        selected_language = countries_language[code];
                    }
                }
                htmlSelectElement.append($('<option>', optionAttributes).text(country));
            });
            return htmlSelectElement;
        };
        var buildDropDownButton = function () {

            var selectedText = $(htmlSelect).find('option').first().text();
            var selectedValue = $(htmlSelect).find('option').first().val();
            selectedText = plugin.selected.text || selectedText;
            selectedValue = plugin.selected.value || selectedValue;
            var $selectedLabel = $('<i/>').addClass('flagstrap-icon flagstrap-' + selectedValue.toLowerCase()).css('margin-right', plugin.settings.labelMargin);
            var buttonLabel = $('<span/>')
                    .addClass('flagstrap-selected-' + uniqueId)
                    .html($selectedLabel)
                    .append(selectedText);
            var button = $('<button/>')
                    .attr('type', 'button')
                    .attr('data-toggle', 'dropdown')
                    .attr('id', 'flagstrap-drop-down-' + uniqueId)
                    .addClass('btn ' + plugin.settings.buttonType + ' ' + plugin.settings.buttonSize + ' dropdown-toggle')
                    .html(buttonLabel);
            $('<span/>')
                    .addClass('caret')
                    .css('margin-left', plugin.settings.labelMargin)
                    .insertAfter(buttonLabel);
            return button;
        };
        var buildDropDownButtonItemList = function () {
            var items = $('<ul/>')
                    .attr('id', 'flagstrap-drop-down-' + uniqueId + '-list')
                    .attr('aria-labelled-by', 'flagstrap-drop-down-' + uniqueId)
                    .addClass('dropdown-menu');
            if (plugin.settings.scrollable) {
                items.css('height', 'auto')
                        .css('max-height', plugin.settings.scrollableHeight)
                        .css('overflow-x', 'hidden');
            }

            // Populate the bootstrap dropdown item list
            $(htmlSelect).find('option').each(function () {

                // Get original select option values and labels
                var text = $(this).text();
                var value = $(this).val();
                // Build the flag icon
                var flagIcon = $('<i/>').addClass('flagstrap-icon flagstrap-' + value.toLowerCase()).css('margin-right', plugin.settings.labelMargin);
                // Build a clickable drop down option item, insert the flag and label, attach click event
                var flagStrapItem = $('<a/>')
                        .attr('data-val', $(this).val())
                        .html(flagIcon)
                        .append(text)
                        .on('click', function (e) {
                            $('#' + plugin.settings.inputName + '-language').val(countries_language[$(this).data('val')]);
                            $(htmlSelect).find('option').removeAttr('selected');
                            $(htmlSelect).find('option[value="' + $(this).data('val') + '"]').attr("selected", "selected");
                            $(htmlSelect).trigger('change');
                            $('.flagstrap-selected-' + uniqueId).html($(this).html());
                            if ($('#' + plugin.settings.inputName).data("is-submit")) {
                                $(this).parents("form").submit();
                            }
                            e.preventDefault();
                        });
                // Make it a list item
                var listItem = $('<li/>').prepend(flagStrapItem);
                // Append it to the drop down item list
                items.append(listItem);
            });
            return items;
        };
        var buildSearchText = function () {
            var search_text = $('<input/>')
                    .attr('type', 'search')
                    .attr('data-toggle', 'dropdown')
                    .attr('id', 'flagstrap-search-country-' + uniqueId)
                    .attr('autocomplete', 'off')
                    .addClass(plugin.settings.buttonSize + ' dropdown-toggle')
                    .on('keyup', function (e) {
                        var search_txt = $(this).val();
                        $(".dropdown-menu li").each(function (index) {
                            var li_html = $(this).html();
                            if (li_html.toLowerCase().indexOf(search_txt.toLowerCase()) == -1) {
                                $(this).hide();
                            } else {
                                $(this).show();
                            }
                        });
                        e.preventDefault();
                    });
            return search_text;
        };
        function generateId(length) {
            var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');
            if (!length) {
                length = Math.floor(Math.random() * chars.length);
            }

            var str = '';
            for (var i = 0; i < length; i++) {
                str += chars[Math.floor(Math.random() * chars.length)];
            }
            return str;
        }

        plugin.init();
    };
    $.fn.flagStrap = function (options) {

        return this.each(function (i) {
            if ($(this).data('flagStrap') === undefined) {
                $(this).data('flagStrap', new $.flagStrap(this, options, i));
            }
        });


    }

})(jQuery);