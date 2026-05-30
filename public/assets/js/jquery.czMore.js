/*
Title: Cozeit More plugin by Yasir Atabani
Documentation: na
Author: Yasir O. Atabani
Website: http://www.cozeit.com
Twitter: @yatabani

MIT License, https://github.com/cozeit/czMore/blob/master/LICENSE.md
*/
var DynamicFieldPlugin = {
  defaults: {
    max: 50,
    min: 0,
    onLoad: null,
    onAdd: null,
    onDelete: null,
    styleOverride: false,
    countFieldPrefix: "_czMore_txtCount",
  },
  init: function (element, options) {
    //Update unset options with defaults if needed
    options = $.extend({}, this.defaults, options);
    $(element).bind("onAdd", function (event, data) {
      options.onAdd.call(event, data);
    });
    $(element).bind("onLoad", function (event, data) {
      options.onLoad.call(event, data);
    });
    $(element).bind("onDelete", function (event, data) {
      options.onDelete.call(event, data);
    });

    //Executing functionality on all selected elements
    // return $element.each(function () {
    var obj = $(element);
    var el = element[0];

    var i = recordsetCount();
    var divPlus =
      '<i id="' +
      el.id +
      'btnPlus" class="mdi mdi-plus-circle-outline mdi-24px btnPlus" />';
    var count =
      '<input id="' +
      el.id +
      options.countFieldPrefix +
      '" name="' +
      el.id +
      options.countFieldPrefix +
      '" type="hidden" value="0" size="5" />';

    obj.before(count);
    var recordset = $("#" + el.id).children(".first");
    obj.after(divPlus);
    //var set = recordset.children(".recordset").children().first();
    var btnPlus = obj.siblings("#" + el.id + "btnPlus");

    if (!options.styleOverride) {
      btnPlus.css({
        float: "right",
        "margin-top": "-25px",
        border: "0px",
        //'background-image': 'url("img/add.png")',
        "background-position": "center center",
        "background-repeat": "no-repeat",
        height: "25px",
        width: "25px",
        cursor: "pointer",
      });
    }

    if (recordset.length) {
      var str = "#" + el.id + "btnPlus";
      $(document).on("click", str, function () {
        if (isMaxRecordset()) {
          return false;
        }
        var i = obj.children(".recordset").length;
        var item = recordset.clone().html();
        i++;
        var customLabel = $('#czContainer').attr('custom_label');
        item = item.replace(new RegExp("--" + customLabel + " 0--", "g"), "--" + customLabel + " " + i + "--");
        item = item.replace(/\[([0-9]\d{0})\]/g, "[" + i + "]");
        item = item.replace(/\_([0-9]\d{0})\_/g, "_" + i + "_");
        //$(element).html(item);
        //item = $(item).children().first();
        //item = $(item).parent();

        $("#" + el.id).append(item);
        loadMinus(obj.children().last(), el.id);
        minusClick(obj.children().last(), el.id);

        if (options.onAdd != null) {
          var matches = el.id.match(/\d+/);
          options.onAdd.call(obj, [i, matches ? parseInt(matches[0]) : ""]);
        }

        obj.siblings("input[name$='" + options.countFieldPrefix + "']").val(i);
        return false;
      });
      recordset.remove();
      for (var j = 0; j <= i; j++) {
        loadMinus(obj.children()[j], el.id);
        minusClick(obj.children()[j], el.id);
        if (options.onAdd != null) {
          obj.trigger("onAdd", j);
        }
      }

      if (options.onLoad != null) {
        obj.trigger("onLoad", i);
      }
      //obj.bind("onAdd", function (event, data) {
      //If you had passed anything in your trigger function, you can grab it using the second parameter in the callback function.
      //});
    }

    function resetNumbering() {
      $(obj)
        .children(".recordset")
        .each(function (index, element) {
          $(element)
            .find(
              "input:text, input:password, input:file, input:hidden, select, textarea, h6, label"
            )
            .each(function () {
              if (this.name) {
                var old_name = this.name;
                var new_name = old_name.replace(
                  /\[([0-9]\d{0})\]/g,
                  "[" + (index + 1) + "]"
                );
                this.id = this.name = new_name;
              }

              if ($(this).is("label")) {
                var currentFor = $(this).attr("for");
                if (currentFor) {
                    var newFor = currentFor.replace(/\[(\d+)\]/g, "[" + (index + 1) + "]");
                    $(this).attr("for", newFor);
                }
            }
            

              if ($(this).prop("nodeName") === "H6") {
                var regex = new RegExp($(this).text(), "g");
                var customLabel = $('#czContainer').attr('custom_label');
                $(this).text(
                  $(this)
                    .text()
                    .replace(regex, "--"+customLabel+" " + (index + 1) + "--")
                );
              }
            });
          index++;
          minusClick(element);
        });
    }

    function loadMinus(recordset, id) {
      var divMinus =
        '<i id="' +
        id +
        'btnMinus" class="mdi mdi-minus-circle-outline mdi-24px btnMinus" />';
      $(recordset).children().first().before(divMinus);
      var btnMinus = $(recordset).children("#" + id + "btnMinus");
      if (!options.styleOverride) {
        btnMinus.css({
          float: "right",
          border: "0px",
          "margin-top": "-8px",
          //'background-image': 'url("img/remove.png")',
          "background-position": "center center",
          "background-repeat": "no-repeat",
          height: "25px",
          width: "25px",
          cursor: "pointer",
        });
      }
    }

    function minusClick(recordset, dayid) {
      $(recordset)
        .children("#" + dayid + "btnMinus")
        .click(function () {
          var i = recordsetCount();
          var id = $(recordset).attr("data-id");
          $(recordset).remove();
          resetNumbering();
          obj
            .siblings("input[name$='" + options.countFieldPrefix + "']")
            .val(obj.children(".recordset").length);
          i--;
          if (options.onDelete != null) {
            if (id != null) obj.trigger("onDelete", id);
            if (obj.children(".recordset").length == 0)
              obj.trigger("onDelete", i);
          }
        });
    }

    function recordsetCount() {
      return obj.children(".recordset").length;
    }

    function isMaxRecordset() {
      return recordsetCount() >= options.max;
    }
  },
  delete: function (id) {
    $(document).off("click", "#" + id + "btnPlus");
    $(document).off("#" + id);
  },
};
