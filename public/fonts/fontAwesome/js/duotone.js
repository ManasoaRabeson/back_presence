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
    
    "file-invoice": [384,512,[],"f570",["M0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-288-128 0c-17.7 0-32-14.3-32-32L224 0 64 0C28.7 0 0 28.7 0 64zM64 80c0-8.8 7.2-16 16-16l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16L80 96c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16zm0 112c0-17.7 14.3-32 32-32l192 0c17.7 0 32 14.3 32 32l0 64c0 17.7-14.3 32-32 32L96 352c-17.7 0-32-14.3-32-32l0-64zM224 432c0-8.8 7.2-16 16-16l64 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16z","M384 160L224 0l0 128c0 17.7 14.3 32 32 32l128 0zM96 224c-17.7 0-32 14.3-32 32l0 64c0 17.7 14.3 32 32 32l192 0c17.7 0 32-14.3 32-32l0-64c0-17.7-14.3-32-32-32L96 224zm0 32l192 0 0 64L96 320l0-64zM240 416c-8.8 0-16 7.2-16 16s7.2 16 16 16l64 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-64 0z"]],
    "file-invoice-dollar": [384,512,[],"f571",["M0 64C0 28.7 28.7 0 64 0L224 0c0 42.7 0 85.3 0 128c0 .6 0 1.1 0 1.6c0 .5 .1 1.1 .1 1.6c.1 1.1 .3 2.1 .5 3.2c.2 1 .5 2.1 .8 3.1c.2 .5 .3 1 .5 1.5s.4 1 .6 1.5c1.6 3.8 4 7.3 6.9 10.2s6.3 5.2 10.2 6.9c1.9 .8 3.9 1.4 6 1.9c1 .2 2.1 .4 3.2 .5c.5 .1 1.1 .1 1.6 .1s1.1 0 1.6 0l128 0 0 288c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 64zM64 80c0 8.8 7.2 16 16 16l64 0c8.8 0 16-7.2 16-16s-7.2-16-16-16L80 64c-8.8 0-16 7.2-16 16zm0 64c0 8.8 7.2 16 16 16l64 0c8.8 0 16-7.2 16-16s-7.2-16-16-16l-64 0c-8.8 0-16 7.2-16 16zm64.2 150.9c-.1 1.2-.1 2.3-.1 3.6c0 1.2 .1 2.3 .1 3.4s.2 2.2 .4 3.2c.3 2.1 .8 4.1 1.4 6c1.2 3.8 2.9 7.2 5 10.3c4.2 6.1 9.8 10.9 15.8 14.5c10.5 6.3 23.8 10.4 34.8 13.7c.5 .2 1.1 .3 1.6 .5c12.5 3.8 22.2 6.9 29 11.2c5.9 3.7 7.7 7 7.7 11.6c.1 6.6-2.7 10.8-7.8 14c-5.8 3.6-14.5 5.6-23.7 5.3c-11.8-.4-22.7-4.1-36.3-8.7c-2.3-.8-4.7-1.6-7.1-2.4c-8.4-2.8-17.4 1.7-20.2 10.1c-.3 .8-.5 1.7-.6 2.5c-.1 .4-.1 .8-.2 1.3c0 .2 0 .4-.1 1.3l.1 1.3c0 0 0 .6 .1 1.2c.1 .8 .3 1.6 .6 2.4c.5 1.6 1.2 3 2.2 4.4c1.9 2.7 4.6 4.8 8 5.9c2 .7 4.1 1.4 6.2 2.1c9.2 3.2 19.7 6.8 30.9 8.9c0 5.9 0 11.9 0 17.8c0 .6 0 1.1 .1 1.6s.1 1.1 .2 1.6c.2 1 .5 2 .9 3c.8 1.9 2 3.6 3.4 5.1c2.9 2.9 6.9 4.7 11.3 4.7c8.8 0 16-7.2 16-16l0-17.1c8.7-1.4 17.4-4.3 25.1-9.1c6.6-4.1 12.4-9.7 16.5-16.6c2-3.5 3.7-7.3 4.8-11.4c.5-2.1 1-4.2 1.2-6.5c.1-1.1 .2-2.3 .3-3.4c.1-1.1 .1-2.3 .1-3.5c-.2-18.6-10.6-30.7-22.7-38.3c-11-6.9-25-11.2-36.3-14.6l-.5-.1c-12.6-3.8-22.3-6.8-29.2-10.9c-3-1.8-4.8-3.4-5.9-5c-.5-.8-.9-1.6-1.1-2.4c-.1-.4-.2-.8-.2-1.3c0-.4-.1-.8-.1-1.2c0-.8 0-1.4 .1-2.1s.2-1.3 .4-1.8c.3-1.2 .8-2.3 1.5-3.3c1.3-2.1 3.4-3.9 6.2-5.6c6.2-3.7 15.1-5.7 23.6-5.5c10.1 .2 21 2.3 32.1 5.3c8.5 2.3 17.3-2.8 19.6-11.3s-2.8-17.3-11.3-19.6c-7.5-2-15.6-3.9-24.1-5.1c0-5.8 0-11.5 0-17.3c0-8.8-7.2-16-16-16c-4.4 0-8.4 1.8-11.3 4.7c-1.4 1.4-2.6 3.2-3.4 5.1c-.4 1-.7 2-.9 3c-.1 .5-.2 1.1-.2 1.6s-.1 1.1-.1 1.5c0 5.9 0 11.7 0 17.5c-8.3 1.5-16.7 4.3-24.1 8.7c-6.5 3.9-12.5 9.1-16.8 15.9c-2.2 3.4-3.9 7.1-5.1 11.2c-.6 2.1-1.1 4.2-1.4 6.4c-.2 1.1-.3 2.3-.4 3.4z","M384 160L224 0l0 128c0 17.7 14.3 32 32 32l128 0zM208 232c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 17.3c-8.3 1.5-16.7 4.3-24.1 8.7c-13 7.7-23.9 21.1-23.8 40.5c.1 18.4 10.8 30.1 22.7 37.3c10.5 6.3 23.8 10.4 34.8 13.7c0 0 0 0 0 0l1.6 .5c12.5 3.8 22.2 6.9 29 11.2c5.9 3.7 7.7 7 7.7 11.6c.1 6.6-2.7 10.8-7.8 14c-5.8 3.6-14.5 5.6-23.7 5.3c-11.8-.4-22.7-4.1-36.3-8.7c0 0 0 0 0 0s0 0 0 0s0 0 0 0c-2.3-.8-4.7-1.6-7.1-2.4c-8.4-2.8-17.4 1.7-20.2 10.1s1.7 17.4 10.1 20.2c2 .7 4.1 1.4 6.2 2.1c0 0 0 0 0 0c9.2 3.2 19.7 6.8 30.9 8.9l0 17.8c0 8.8 7.2 16 16 16s16-7.2 16-16l0-17.1c8.7-1.4 17.4-4.3 25.1-9.1c13.3-8.3 23.2-22.2 22.9-41.6c-.2-18.5-10.6-30.6-22.7-38.2c-11-6.9-25-11.2-36.3-14.6c0 0 0 0 0 0s0 0 0 0l-.5-.1c-12.6-3.8-22.3-6.8-29.2-10.9c-6-3.6-7.2-6.4-7.3-10.1c0-5.4 2.4-9.4 8.1-12.8c6.2-3.7 15.1-5.7 23.6-5.5c10.1 .2 21 2.3 32.1 5.3c8.5 2.3 17.3-2.8 19.6-11.3s-2.8-17.3-11.3-19.6c-7.5-2-15.6-3.9-24.1-5.1l0-17.3z"]],
    "gem": [512,512,["128142"],"f3a5",["M4.7 185.8c-6.8 9.2-6.1 21.9 1.5 30.4l232 256c4.5 5 11 7.9 17.8 7.9s13.2-2.9 17.8-7.9l232-256c4.1-4.6 6.2-10.3 6.2-16.1c0-5-1.5-10-4.7-14.2l-112-152C390.8 27.6 383.6 24 376 24L136 24c-7.6 0-14.8 3.6-19.3 9.8l-112 152zm51.5 12.7c.1-.5 .2-1 .4-1.4c.4-.9 .9-1.7 1.5-2.5c1.3-1.4 3.1-2.4 5.2-2.6c49.1-4.1 98.1-8.2 147.2-12.3L153.1 84.1c-2.1-3.5-1.2-8.1 2.1-10.5s7.9-2 10.7 1c30 32.5 60.1 65.1 90.1 97.6c30-32.5 60.1-65.1 90.1-97.6c2.8-3 7.4-3.4 10.7-1s4.2 7 2.1 10.5c-19.1 31.9-38.3 63.8-57.4 95.6c49.1 4.1 98.1 8.2 147.2 12.3c4.1 .3 7.3 3.8 7.3 8s-3.2 7.6-7.3 8c-64 5.3-128 10.7-192 16c-.2 0-.4 0-.7 0s-.4 0-.7 0c-64-5.3-128-10.7-192-16c-2.1-.2-3.9-1.1-5.2-2.6c-.7-.7-1.2-1.6-1.6-2.5c-.2-.5-.3-.9-.4-1.4c0-.2-.1-.5-.1-.7s0-.5 0-.9c0-.2 0-.4 0-.7s.1-.5 .1-.7z","M165.9 74.6c-2.8-3-7.4-3.4-10.7-1s-4.2 7-2.1 10.5l57.4 95.6L63.3 192c-4.1 .3-7.3 3.8-7.3 8s3.2 7.6 7.3 8l192 16c.4 0 .9 0 1.3 0l192-16c4.1-.3 7.3-3.8 7.3-8s-3.2-7.6-7.3-8L301.5 179.8l57.4-95.6c2.1-3.5 1.2-8.1-2.1-10.5s-7.9-2-10.7 1L256 172.2 165.9 74.6z"]],
    "house-chimney": [576,512,["63499","home-lg"],"e3af",["M64 270.5c74.7-65.3 149.3-130.7 224-196L512.1 270.6l.4 201.3c0 22.1-17.9 40.1-40 40.1L392 512c-22.1 0-40-17.9-40-40l0-88.3c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32 14.3-32 32l0 88.3c0 22.1-17.9 40-40 40l-79.9 0c-22.1 0-40-17.9-40-40L64 270.5z","M309.1 7.9C297-2.6 279-2.6 266.9 7.9l-256 224c-13.3 11.6-14.6 31.9-3 45.2s31.9 14.6 45.2 3L288 74.5 522.9 280.1c13.3 11.6 33.5 10.3 45.2-3s10.3-33.5-3-45.2L512 185.5 512 64c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32l0 37.5L309.1 7.9z"]],
    "sack-dollar": [512,512,["128176"],"f81d",["M0 416c0 53 43 96 96 96l320 0c53 0 96-43 96-96c0-165.1-122.3-243.3-179-279.6c-4.8-3.1-9.2-5.9-13-8.4l-128 0c-3.8 2.5-8.1 5.3-13 8.4C122.3 172.7 0 250.9 0 416zM144.6 24.9L192 96l128 0 47.4-71.1C374.5 14.2 366.9 0 354.1 0L157.9 0c-12.8 0-20.4 14.2-13.3 24.9zm43.6 253.7c.1-1.3 .2-2.5 .4-3.7c.3-2.4 .9-4.8 1.5-7c1.3-4.5 3.2-8.5 5.6-12.2c4.7-7.3 11.2-13 18.2-17.1c6.9-4.1 14.5-6.8 22.2-8.5l0-14c0-11 9-20 20-20s20 9 20 20l0 13.9c7.5 1.2 14.6 2.9 21.1 4.7c10.7 2.8 17 13.8 14.2 24.5s-13.8 17-24.5 14.2c-11-2.9-21.6-5-31.2-5.2c-7.9-.1-16 1.8-21.5 5c-4.8 2.8-6.2 5.6-6.2 9.3c0 1.8 .1 3.5 5.3 6.7c6.3 3.8 15.5 6.7 28.3 10.5l.7 .2c11.2 3.4 25.6 7.7 37.1 15c12.9 8.1 24.3 21.3 24.6 41.6c0 .7 0 1.3 0 1.9s0 1.3-.1 1.9c-.1 1.3-.2 2.5-.3 3.7c-.3 2.4-.7 4.8-1.3 7c-1.2 4.5-3 8.6-5.2 12.4c-4.4 7.5-10.7 13.5-17.9 18c-7.2 4.5-15.2 7.3-23.2 9l0 13.8c0 11-9 20-20 20s-20-9-20-20l0-14.6c-10.3-2.2-20-5.5-28.3-8.4c-2.1-.7-4.1-1.4-6.1-2.1c-10.5-3.5-16.1-14.8-12.6-25.3s14.8-16.1 25.3-12.6c2.5 .8 4.9 1.7 7.2 2.4c13.6 4.6 24 8.1 35.1 8.5c8.6 .3 16.5-1.6 21.4-4.7c4.1-2.5 6-5.5 5.9-10.5c0-2.9-.8-5-5.9-8.2c-6.3-4-15.4-6.9-28-10.7l-1.7-.5c-10.9-3.3-24.6-7.4-35.6-14c-12.7-7.7-24.6-20.5-24.7-40.7c0-.7 0-1.3 0-2s.1-1.3 .1-1.9z","M192 96c-8.8 0-16 7.2-16 16s7.2 16 16 16l128 0c8.8 0 16-7.2 16-16s-7.2-16-16-16L192 96zm84 120c0-11-9-20-20-20s-20 9-20 20l0 14c-7.6 1.7-15.2 4.4-22.2 8.5c-13.9 8.3-25.9 22.8-25.8 43.9c.1 20.3 12 33.1 24.7 40.7c11 6.6 24.7 10.8 35.6 14l1.7 .5c12.6 3.8 21.8 6.8 28 10.7c5.1 3.2 5.8 5.4 5.9 8.2c.1 5-1.8 8-5.9 10.5c-5 3.1-12.9 5-21.4 4.7c-11.1-.4-21.5-3.9-35.1-8.5c0 0 0 0 0 0c-2.3-.8-4.7-1.6-7.2-2.4c-10.5-3.5-21.8 2.2-25.3 12.6s2.2 21.8 12.6 25.3c1.9 .6 4 1.3 6.1 2.1c0 0 0 0 0 0s0 0 0 0c8.3 2.9 17.9 6.2 28.2 8.4l0 14.6c0 11 9 20 20 20s20-9 20-20l0-13.8c8-1.7 16-4.5 23.2-9c14.3-8.9 25.1-24.1 24.8-45c-.3-20.3-11.7-33.4-24.6-41.6c-11.5-7.2-25.9-11.6-37.1-15l-.7-.2c-12.8-3.9-21.9-6.7-28.3-10.5c-5.2-3.1-5.3-4.9-5.3-6.7c0-3.7 1.4-6.5 6.2-9.3c5.4-3.2 13.6-5.1 21.5-5c9.6 .1 20.2 2.2 31.2 5.2c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-6.5-1.7-13.7-3.4-21.1-4.7l0-13.9z"]]

  };
  var prefixes = [null    ,'fad',
    ,'fa-duotone'
];
  bunker(() => {
    for (const prefix of prefixes) {
      if (!prefix) continue;
      defineIcons(prefix, icons);
    }
  });

}());
