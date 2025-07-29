(function () {
  'use strict';

  let _WINDOW = {};
  let _DOCUMENT = {};
  try {
    if (typeof window !== 'undefined') _WINDOW = window;
    if (typeof document !== 'undefined') _DOCUMENT = document;
  } catch (e) {}
  const {
    userAgent = ''
  } = _WINDOW.navigator || {};
  const WINDOW = _WINDOW;
  const DOCUMENT = _DOCUMENT;
  const IS_BROWSER = !!WINDOW.document;
  const IS_DOM = !!DOCUMENT.documentElement && !!DOCUMENT.head && typeof DOCUMENT.addEventListener === 'function' && typeof DOCUMENT.createElement === 'function';
  const IS_IE = ~userAgent.indexOf('MSIE') || ~userAgent.indexOf('Trident/');

  function _defineProperty(e, r, t) {
    return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, {
      value: t,
      enumerable: !0,
      configurable: !0,
      writable: !0
    }) : e[r] = t, e;
  }
  function ownKeys(e, r) {
    var t = Object.keys(e);
    if (Object.getOwnPropertySymbols) {
      var o = Object.getOwnPropertySymbols(e);
      r && (o = o.filter(function (r) {
        return Object.getOwnPropertyDescriptor(e, r).enumerable;
      })), t.push.apply(t, o);
    }
    return t;
  }
  function _objectSpread2(e) {
    for (var r = 1; r < arguments.length; r++) {
      var t = null != arguments[r] ? arguments[r] : {};
      r % 2 ? ownKeys(Object(t), !0).forEach(function (r) {
        _defineProperty(e, r, t[r]);
      }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) {
        Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r));
      });
    }
    return e;
  }
  function _toPrimitive(t, r) {
    if ("object" != typeof t || !t) return t;
    var e = t[Symbol.toPrimitive];
    if (void 0 !== e) {
      var i = e.call(t, r || "default");
      if ("object" != typeof i) return i;
      throw new TypeError("@@toPrimitive must return a primitive value.");
    }
    return ("string" === r ? String : Number)(t);
  }
  function _toPropertyKey(t) {
    var i = _toPrimitive(t, "string");
    return "symbol" == typeof i ? i : i + "";
  }

  var S = {
      classic: {
        fa: "solid",
        fas: "solid",
        "fa-solid": "solid",
        far: "regular",
        "fa-regular": "regular",
        fal: "light",
        "fa-light": "light",
        fat: "thin",
        "fa-thin": "thin",
        fab: "brands",
        "fa-brands": "brands"
      },
      duotone: {
        fa: "solid",
        fad: "solid",
        "fa-solid": "solid",
        "fa-duotone": "solid",
        fadr: "regular",
        "fa-regular": "regular",
        fadl: "light",
        "fa-light": "light",
        fadt: "thin",
        "fa-thin": "thin"
      },
      sharp: {
        fa: "solid",
        fass: "solid",
        "fa-solid": "solid",
        fasr: "regular",
        "fa-regular": "regular",
        fasl: "light",
        "fa-light": "light",
        fast: "thin",
        "fa-thin": "thin"
      },
      "sharp-duotone": {
        fa: "solid",
        fasds: "solid",
        "fa-solid": "solid",
        fasdr: "regular",
        "fa-regular": "regular",
        fasdl: "light",
        "fa-light": "light",
        fasdt: "thin",
        "fa-thin": "thin"
      }
    };
  var s = "classic";
  var G = {
      classic: {
        900: "fas",
        400: "far",
        normal: "far",
        300: "fal",
        100: "fat"
      },
      duotone: {
        900: "fad",
        400: "fadr",
        300: "fadl",
        100: "fadt"
      },
      sharp: {
        900: "fass",
        400: "fasr",
        300: "fasl",
        100: "fast"
      },
      "sharp-duotone": {
        900: "fasds",
        400: "fasdr",
        300: "fasdl",
        100: "fasdt"
      }
    };
  var xt = {
      classic: {
        solid: "fas",
        regular: "far",
        light: "fal",
        thin: "fat",
        brands: "fab"
      },
      duotone: {
        solid: "fad",
        regular: "fadr",
        light: "fadl",
        thin: "fadt"
      },
      sharp: {
        solid: "fass",
        regular: "fasr",
        light: "fasl",
        thin: "fast"
      },
      "sharp-duotone": {
        solid: "fasds",
        regular: "fasdr",
        light: "fasdl",
        thin: "fasdt"
      }
    };
  var St = {
      kit: {
        fak: "kit",
        "fa-kit": "kit"
      },
      "kit-duotone": {
        fakd: "kit-duotone",
        "fa-kit-duotone": "kit-duotone"
      }
    };
  var Ct = {
    kit: {
      "fa-kit": "fak"
    },
    "kit-duotone": {
      "fa-kit-duotone": "fakd"
    }
  };
  var Wt = {
      kit: {
        fak: "fa-kit"
      },
      "kit-duotone": {
        fakd: "fa-kit-duotone"
      }
    };
  var Et = {
      kit: {
        kit: "fak"
      },
      "kit-duotone": {
        "kit-duotone": "fakd"
      }
    };

  var ua = {
      classic: {
        "fa-brands": "fab",
        "fa-duotone": "fad",
        "fa-light": "fal",
        "fa-regular": "far",
        "fa-solid": "fas",
        "fa-thin": "fat"
      },
      duotone: {
        "fa-regular": "fadr",
        "fa-light": "fadl",
        "fa-thin": "fadt"
      },
      sharp: {
        "fa-solid": "fass",
        "fa-regular": "fasr",
        "fa-light": "fasl",
        "fa-thin": "fast"
      },
      "sharp-duotone": {
        "fa-solid": "fasds",
        "fa-regular": "fasdr",
        "fa-light": "fasdl",
        "fa-thin": "fasdt"
      }
    },
    ga = {
      classic: {
        fab: "fa-brands",
        fad: "fa-duotone",
        fal: "fa-light",
        far: "fa-regular",
        fas: "fa-solid",
        fat: "fa-thin"
      },
      duotone: {
        fadr: "fa-regular",
        fadl: "fa-light",
        fadt: "fa-thin"
      },
      sharp: {
        fass: "fa-solid",
        fasr: "fa-regular",
        fasl: "fa-light",
        fast: "fa-thin"
      },
      "sharp-duotone": {
        fasds: "fa-solid",
        fasdr: "fa-regular",
        fasdl: "fa-light",
        fasdt: "fa-thin"
      }
    };

  const NAMESPACE_IDENTIFIER = '___FONT_AWESOME___';
  const PRODUCTION = (() => {
    try {
      return "production" === 'production';
    } catch (e$$1) {
      return false;
    }
  })();
  function familyProxy(obj) {
    // Defaults to the classic family if family is not available
    return new Proxy(obj, {
      get(target, prop) {
        return prop in target ? target[prop] : target[s];
      }
    });
  }
  const _PREFIX_TO_STYLE = _objectSpread2({}, S);

  // We changed FACSSClassesToStyleId in the icons repo to be canonical and as such, "classic" family does not have any
  // duotone styles.  But we do still need duotone in _PREFIX_TO_STYLE below, so we are manually adding
  // {'fa-duotone': 'duotone'}
  _PREFIX_TO_STYLE[s] = _objectSpread2(_objectSpread2(_objectSpread2(_objectSpread2({}, {
    'fa-duotone': 'duotone'
  }), S[s]), St['kit']), St['kit-duotone']);
  const PREFIX_TO_STYLE = familyProxy(_PREFIX_TO_STYLE);
  const _STYLE_TO_PREFIX = _objectSpread2({}, xt);

  // We changed FAStyleIdToShortPrefixId in the icons repo to be canonical and as such, "classic" family does not have any
  // duotone styles.  But we do still need duotone in _STYLE_TO_PREFIX below, so we are manually adding {duotone: 'fad'}
  _STYLE_TO_PREFIX[s] = _objectSpread2(_objectSpread2(_objectSpread2(_objectSpread2({}, {
    duotone: 'fad'
  }), _STYLE_TO_PREFIX[s]), Et['kit']), Et['kit-duotone']);
  const STYLE_TO_PREFIX = familyProxy(_STYLE_TO_PREFIX);
  const _PREFIX_TO_LONG_STYLE = _objectSpread2({}, ga);
  _PREFIX_TO_LONG_STYLE[s] = _objectSpread2(_objectSpread2({}, _PREFIX_TO_LONG_STYLE[s]), Wt['kit']);
  const PREFIX_TO_LONG_STYLE = familyProxy(_PREFIX_TO_LONG_STYLE);
  const _LONG_STYLE_TO_PREFIX = _objectSpread2({}, ua);
  _LONG_STYLE_TO_PREFIX[s] = _objectSpread2(_objectSpread2({}, _LONG_STYLE_TO_PREFIX[s]), Ct['kit']);
  const LONG_STYLE_TO_PREFIX = familyProxy(_LONG_STYLE_TO_PREFIX);
  const _FONT_WEIGHT_TO_PREFIX = _objectSpread2({}, G);
  const FONT_WEIGHT_TO_PREFIX = familyProxy(_FONT_WEIGHT_TO_PREFIX);

  function bunker(fn) {
    try {
      for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
        args[_key - 1] = arguments[_key];
      }
      fn(...args);
    } catch (e) {
      if (!PRODUCTION) {
        throw e;
      }
    }
  }

  const w = WINDOW || {};
  if (!w[NAMESPACE_IDENTIFIER]) w[NAMESPACE_IDENTIFIER] = {};
  if (!w[NAMESPACE_IDENTIFIER].styles) w[NAMESPACE_IDENTIFIER].styles = {};
  if (!w[NAMESPACE_IDENTIFIER].hooks) w[NAMESPACE_IDENTIFIER].hooks = {};
  if (!w[NAMESPACE_IDENTIFIER].shims) w[NAMESPACE_IDENTIFIER].shims = [];
  var namespace = w[NAMESPACE_IDENTIFIER];

  function normalizeIcons(icons) {
    return Object.keys(icons).reduce((acc, iconName) => {
      const icon = icons[iconName];
      const expanded = !!icon.icon;
      if (expanded) {
        acc[icon.iconName] = icon.icon;
      } else {
        acc[iconName] = icon;
      }
      return acc;
    }, {});
  }
  function defineIcons(prefix, icons) {
    let params = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    const {
      skipHooks = false
    } = params;
    const normalized = normalizeIcons(icons);
    if (typeof namespace.hooks.addPack === 'function' && !skipHooks) {
      namespace.hooks.addPack(prefix, normalizeIcons(icons));
    } else {
      namespace.styles[prefix] = _objectSpread2(_objectSpread2({}, namespace.styles[prefix] || {}), normalized);
    }

    /**
     * Font Awesome 4 used the prefix of `fa` for all icons. With the introduction
     * of new styles we needed to differentiate between them. Prefix `fa` is now an alias
     * for `fas` so we'll ease the upgrade process for our users by automatically defining
     * this as well.
     */
    if (prefix === 'fas') {
      defineIcons('fa', icons);
    }
  }

  var icons = {
    
    "formafusion": [1375,512,[],"e000","M259.6 59.3h0c0 .5-.1 1.6-.3 3.1c-.1 .9-.2 1.9-.4 3.1c0 .1 0 .2 0 .3c-2.7 19.8-12.3 76.2-15.7 92.4c0 0-16.2 2.9-36.2 8.1h0s0 0-.1 0c-13.2 3.3-45 12.3-70.1 24h0c-31.2 13.7-60.2 31.5-87.6 52.1c-3.3 2.5-6.6 4.9-11 8.2c.7-2.9 1-4.6 1.6-6.2c17.2-44.8 43.5-83.2 80-114.6c28.5-24.5 60.8-42.6 95.6-56.4c12-4.8 24.2-8.8 36.5-12.6c1-.3 1.9-.6 2.9-.9h0c.9-.3 2.9-.9 4.9-1.5c0 .2 0 .5-.1 .7zM234.3 198.5c-.2 1.1-.4 2.4-.6 3.8c0 1-.3 2-.5 3c0 0 0 0 0 0c-.1 .8-.3 1.7-.4 2.5c-.1 .8-.3 1.7-.4 2.5c0 .1 0 .3-.1 .4c-.1 .6-.2 1.3-.3 1.9c-.1 .3-.1 .7-.2 1c-.1 .4-.1 .8-.2 1.2c-.1 .6-.2 1.2-.3 1.8c-.3 2.1-.6 3.9-.8 5.4c0 .2-.1 .5-.1 .7c0 .2-.1 .4-.1 .7c-1.7 11.1-3.5 22.2-5.2 33.2c-1.6 10-3.1 20-4.7 30c-.4 2.3-.9 4.6-1.4 7.2c-2.2 .8-4.4 1.6-6.6 2.3c-24.4 7.5-48.2 16.3-70.7 28.5c-14.2 7.7-27.6 16.6-39.3 27.9c-13.6 13.1-23.6 28.2-28 46.8c-3.3 14-2.9 27.9 .3 41.7c1.7 7.6 4.6 14.9 6.9 22.3c.5 1.5 .9 2.9 1.4 4.4c-20-22.8-36.9-47.3-46.8-76c-4.8-13.9-7.5-28.1-6.8-42.9c1-20.5 8.3-38.4 20.7-54.5c8.6-11.2 19-20.7 30.3-29.1c17.8-13.3 37.2-23.7 57.4-32.8c22.7-10.2 46-18.7 69.7-26.1c3-.9 6-1.9 9-2.7c.3-.1 .5-.2 .8-.3c.5-.1 .9-.3 1.4-.4c.3-.1 .7-.2 1-.3c1.2-.4 2.5-.7 3.8-1.1c.7-.2 1.5-.4 2.2-.6c.3-.1 .6-.2 .9-.3c.3-.1 .5-.1 .8-.2c1.7-.5 3.3-1 4.9-1.5c0 0 0 0 0 0c.7-.2 1.4-.4 2-.6zm54.5 110.5l5.6-57.3c2-21.8 13-33.2 33.1-34.2l62.9 .1-2.2 22.3h-55.7c-4.7-.2-8.2 2-10.2 6.7l-.5 4.8 65.4-.1-2.2 22-65-.1-3.7 35.9h-27.5zm170.9 1.1h-43.3c-19.3 .1-27.3-15.4-24.1-46.5c3-31.4 14.7-47.2 35.1-47.4l35.5 .1c21.3-.4 30.3 14.8 26.9 45.5c-1.4 31.8-11.5 47.9-30.3 48.3zm-42.4-31.7c1.3 4.2 4 6.7 8 7.4l25.6-.1c4.3-.5 7.4-2.5 9.1-5.9c2.1-2.4 3.6-8.4 4.4-17.8s.2-15.5-2-18.1c-2-2.7-5.2-4.3-9.5-4.7h-19.7c-5.7 .4-9.7 3.2-12.1 8.3c-1.6 2.3-2.8 7.7-3.7 15.9s-1 13.3-.1 15zm83.3-60.9h63.3c19.1 3.2 28.2 13.5 27.3 31c-.3 12.8-5.4 23-15.3 30.5l12.8 30.1h-30.5l-12.4-28.8h-24.2l-2.8 28.4-27.4 .1 9.2-91.3zm23.9 38.7l33.6 .1c5.1-.1 8.1-2.6 8.8-7.4s-1.6-7.7-7-8.5H526l-1.6 15.8zm69 53l9.2-91.8 81.8 .2c18.1-.5 26.6 9.1 25.6 29l-6.3 62.5h-23.2l5.5-55.4c1.3-6.9-.7-10.2-5.9-10l-12.3 .1-6.6 65.3-23.1 .1 6.5-65.5-19.8-.1-6.5 65.7h-25.1zm117.7-.7l6.2-62.2c2.9-18.7 14.2-28.5 33.8-29.3l30.9 .1c17.9 1.2 26.3 10.9 25.2 29l-6.3 62.5h-24.3l2.8-27.8-40.6-.2-2.7 27.8h-25.2zm31.4-60.2l-1 9.7h40.5l.8-7.6c1.3-7-.7-11-6.1-11.8l-24.5 .1c-6.5 .2-9.7 3.4-9.7 9.7zm66.1 60.7l5.6-57.3c2-21.8 13-33.2 33.1-34.2l62.9 .1-2.2 22.3h-55.7c-4.7-.2-8.2 2-10.2 6.7l-.5 4.8 65.4-.1-2.2 22-65-.1-3.7 35.9h-27.5zm199.1-91.4l-9.2 91.4h-61.8c-17.9 .3-26.3-9.3-25.3-29.1l6.3-62.3h24.2l-6.1 57.1c-1.1 5.9 1.1 8.7 6.5 8.3H976l6.5-65.3 25.2-.1zm89 23.8h-50.5c-3.4 0-6.1 .2-7.9 .5s-3.5 1.7-4.9 4c.4 3 1.6 4.6 3.5 5s4.9 .6 8.9 .6h24.7c9.9 .6 16.8 3.4 20.8 8.6s5.5 12.4 4.6 21.7c-1.5 9.3-4.7 16.1-9.8 20.5s-13.4 6.7-25 6.9h-55.2l2.3-23.4h52.4c4.5 0 8-.2 10.3-.5s4-2.1 4.9-5.2c-.3-2.5-1.5-4-3.6-4.5s-5.6-.8-10.6-.8h-23.9c-10.5 0-18-2.6-22.3-7.7s-6.1-12.2-5.2-21.3c1.7-9.5 5.1-16.4 10.3-20.7s13.4-6.7 24.6-7.2h53.9l-2.3 23.7zm10.4-23.7h26.7l-9.1 91.3h-27.3l9.7-91.3zm96.5 92.5h-43.3c-19.3 .1-27.3-15.4-24.1-46.5c3-31.4 14.7-47.2 35.1-47.4l35.5 .1c21.3-.4 30.3 14.8 26.9 45.5c-1.4 31.8-11.5 47.9-30.3 48.3zm-42.4-31.7c1.3 4.2 4 6.7 8 7.4l25.6-.1c4.3-.5 7.4-2.5 9.1-5.9c2.1-2.4 3.6-8.4 4.4-17.8s.2-15.5-2-18.1c-2-2.7-5.2-4.3-9.5-4.7h-19.7c-5.7 .4-9.7 3.2-12.1 8.3c-1.6 2.3-2.8 7.7-3.7 15.9s-1 13.3-.1 15zm75.1 30.8l9.2-91.8h62c17.9-.4 26.4 9.3 25.4 29.2l-6.3 62.5h-24.3l6.1-57.3c1.1-5.9-1.1-8.7-6.6-8.3H1268l-6.5 65.7h-25.2z"]

  };
  var prefixes = [null    ,'fak',
    ,'fa-kit'
];
  bunker(() => {
    for (const prefix of prefixes) {
      if (!prefix) continue;
      defineIcons(prefix, icons);
    }
  });

}());
