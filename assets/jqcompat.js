/**
 * Provides a few compatibility layers for jQuery to ensure
 * jQuery.Gantt works gracefully.
 */
(function (jQuery) {
  "use strict";
  var returnFalse = function () {
    return false;
  };
  jQuery.extend({
    isNumeric: function (value) {
      return "number" === typeof parseInt(value, 10);
    }
  });
  jQuery.fn.extend({
    on: function (types, selector, data, fn) {
      var type;
      // Decal parameters if selector is not a string
      // Algorithm from jQuery 1.7
      if (data == null && fn == null) {
        // (types, fn)
        fn = selector;
        data = selector = undefined;
      } else if (fn == null) {
        if ("string" === typeof selector) {
          // (types, selector, fn)
          fn = data;
          data = undefined;
        } else {
          // (types, data, fn)
          fn = data;
          data = selector;
          selector = undefined;
        }
      }
      if (fn === false) {
        fn = returnFalse;
      } else if (!fn) {
        return this;
      }
      // Ensure types is an iterable
      if ("string" !== typeof types) {
        for (type in types) {
          this.on(type, selector, data, fn);
        }
        return this;
      } else {
        return this.each(function() {
          if (selector) {
            if (data) {
              jQuery(this).find(selector).bind(types, data, fn);
            } else {
              jQuery(this).find(selector).bind(types, fn);
            }
          } else {
            if (data) {
              jQuery(this).bind(types, data, fn);
            } else {
              jQuery(this).bind(types, fn);
            }
          }
        });
      }
    }
  });
}(jQuery));