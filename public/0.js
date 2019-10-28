(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Map/ListingsMap.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Map/ListingsMap.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _MapboxPopup_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MapboxPopup.vue */ "./resources/js/components/Map/MapboxPopup.vue");
/* harmony import */ var _helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../helpers */ "./resources/js/helpers.js");
/* harmony import */ var _sass_component_listings_map_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../sass/component/listings-map.scss */ "./resources/sass/component/listings-map.scss");
/* harmony import */ var _sass_component_listings_map_scss__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_sass_component_listings_map_scss__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _node_modules_mapbox_gl_dist_mapbox_gl_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/mapbox-gl/dist/mapbox-gl.css */ "./node_modules/mapbox-gl/dist/mapbox-gl.css");
/* harmony import */ var _node_modules_mapbox_gl_dist_mapbox_gl_css__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_node_modules_mapbox_gl_dist_mapbox_gl_css__WEBPACK_IMPORTED_MODULE_3__);
//
//
//
//





var mapboxgl = __webpack_require__(/*! mapbox-gl */ "./node_modules/mapbox-gl/dist/mapbox-gl.js");

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'ListingsMap',
  data: function data() {
    return {
      map: {},
      platform: {},
      mapEvents: {},
      data_points: [],
      icon: null,
      listing_ids: [],
      visible_listings: []
    };
  },
  props: {
    listings: Array,
    appId: {
      type: String,
      "default": 'lQPofquBtIF1aAOEarx7'
    },
    appCode: {
      type: String,
      "default": '28icBNVvG484yi09NA2c1g'
    },
    lat: {
      type: String,
      "default": '43.75171'
    },
    lng: {
      type: String,
      "default": '-88.44867'
    },
    zoom: {
      type: Number,
      "default": 10
    },
    width: {
      type: String,
      "default": '100%'
    },
    height: {
      type: String,
      "default": 'calc(100vh - 50px)'
    },
    svg: {
      type: String,
      "default": null
    }
  },
  created: function created() {
    this.platform = new H.service.Platform({
      'app_id': this.appId,
      'apikey': '7W5ZSgnP_hvci-01R4EbN1_T17e_5x1zVr54MheJxTk'
    });

    if (this.svg) {
      this.icon = new H.map.Icon(this.svg);
    }
  },
  mounted: function mounted() {
    var _this = this;

    if (this.listings) {
      this.visible_listings = this.listings;
    }

    mapboxgl.accessToken = 'pk.eyJ1IjoibWRhaGxrZSIsImEiOiJjazI2bGgzNjUwZzlsM2dxaDd2OXgxZW9yIn0.kKYT-PvLDgQeFZWc2MMOAw';
    this.map = new mapboxgl.Map({
      container: 'listings__map',
      style: 'mapbox://styles/mapbox/streets-v11',
      center: {
        lng: this.lng,
        lat: this.lat
      },
      zoom: this.zoom
    }); // Add zoom and rotation controls to the map.

    this.map.addControl(new mapboxgl.NavigationControl());
    /**
     * When the user stops dragging the map we need to get the coordinates
     * to retrieve any new listings that should be visible on the screen
     *
     * @TODO break out the inner logic to allow for zoom in/out
     */

    this.map.on('dragend', function () {
      var bounds = _this.map.getBounds();

      _this.get_listings_in_bounds(bounds).then(function (results) {
        var listings = results.data.data.listings.data;
        _this.visible_listings = listings;
        var r;
        var popup;

        for (var i in listings) {
          popup = null;

          if (listings.hasOwnProperty(i)) {
            r = listings[i]; // laravel is sending this value back as a string
            // we need to change it to a boolean

            r.isSaved = r.isSaved.toLowerCase() === 'true';

            if (!_this.listing_ids.includes(r.id)) {
              _this.listing_ids.push(r.id);

              popup = _this.create_popup(r);

              _this.add_marker(Object(_helpers__WEBPACK_IMPORTED_MODULE_1__["mapbox_latlng"])(r), popup);
            }
          }
        }
      });
    });
    var l;
    var popup;

    for (var i in this.listings) {
      popup = null;

      if (this.listings.hasOwnProperty(i)) {
        l = this.listings[i];

        if (l.id) {
          this.listing_ids.push(l.id);
          popup = this.create_popup(l);
          this.add_marker(Object(_helpers__WEBPACK_IMPORTED_MODULE_1__["mapbox_latlng"])(l), popup);
        }
      }
    }
  },
  methods: {
    add_marker: function add_marker() {
      var coords = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var popup = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

      if (!coords.lat || !coords.lng) {
        return false;
      }

      var marker = new mapboxgl.Marker().setLngLat({
        lon: coords.lng,
        lat: coords.lat
      }).addTo(this.map);

      if (popup) {
        marker.setPopup(popup);
      }

      return marker;
    },
    add_popup: function add_popup(listing) {
      var popup = new mapboxgl.Popup({
        className: 'listing__popup'
      }).setLngLat(Object(_helpers__WEBPACK_IMPORTED_MODULE_1__["mapbox_latlng"])(listing)).setHTML("<h1>" + listing.title + "</h1>").setMaxWidth("300px").addTo(this.map);
    },
    create_popup: function create_popup(listing) {
      return new mapboxgl.Popup({
        className: 'listing__popup'
      }).setHTML("<h1>" + listing.title + "</h1>").setMaxWidth("300px");
    },
    get_listings_in_bounds: function get_listings_in_bounds(bounds) {
      var query = JSON.stringify(JSON.stringify({
        sw: bounds._sw,
        ne: bounds._ne
      }));
      return axios({
        url: '/graphql',
        type: 'get',
        params: {
          query: "\n\t\t\t\t\t\t\tquery FetchListingsInBounds {\n\t\t\t\t\t\t\t  listings(bounds: " + query + ") {\n\t\t\t\t\t\t\t    data {\n\t\t\t\t\t\t\t      id\n\t\t\t\t\t\t\t      title\n\t\t\t\t\t\t\t      address\n\t\t\t\t\t\t\t      latitude\n\t\t\t\t\t\t\t      longitude\n\t\t\t\t\t\t\t      isSaved\n\t\t\t\t\t\t\t      saveUrl\n\t\t\t\t\t\t\t      removeSavedUrl\n\t\t\t\t\t\t\t      date {\n\t\t\t\t\t\t\t        start\n\t\t\t\t\t\t\t        end\n\t\t\t\t\t\t\t      }\n\t\t\t\t\t\t\t      image {\n\t\t\t\t\t\t\t        name\n\t\t\t\t\t\t\t        path\n\t\t\t\t\t\t\t      }\n\t\t\t\t\t\t\t    }\n\t\t\t\t\t\t\t  }\n\t\t\t\t\t\t\t}\n\t\t\t\t\t"
        }
      });
    },
    save: function save(listing) {
      console.log('save', listing.isSaved);

      if (!listing.isSaved) {
        axios.post(listing.saveUrl).then(function (e) {
          console.log(e);
          listing.isSaved = true;
        });
      }
    },
    remove_saved: function remove_saved(listing) {
      console.log('remove', listing.isSaved);

      if (listing.isSaved) {
        axios.post(listing.removeSavedUrl).then(function (e) {
          listing.isSaved = false;
          console.log(listing);
        });
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Map/ListingsMap.vue?vue&type=template&id=52f93b7f&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Map/ListingsMap.vue?vue&type=template&id=52f93b7f& ***!
  \******************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { attrs: { id: "listings__map" } })
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/Map/ListingsMap.vue":
/*!*****************************************************!*\
  !*** ./resources/js/components/Map/ListingsMap.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ListingsMap_vue_vue_type_template_id_52f93b7f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ListingsMap.vue?vue&type=template&id=52f93b7f& */ "./resources/js/components/Map/ListingsMap.vue?vue&type=template&id=52f93b7f&");
/* harmony import */ var _ListingsMap_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ListingsMap.vue?vue&type=script&lang=js& */ "./resources/js/components/Map/ListingsMap.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ListingsMap_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ListingsMap_vue_vue_type_template_id_52f93b7f___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ListingsMap_vue_vue_type_template_id_52f93b7f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Map/ListingsMap.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Map/ListingsMap.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/Map/ListingsMap.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ListingsMap_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ListingsMap.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Map/ListingsMap.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ListingsMap_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Map/ListingsMap.vue?vue&type=template&id=52f93b7f&":
/*!************************************************************************************!*\
  !*** ./resources/js/components/Map/ListingsMap.vue?vue&type=template&id=52f93b7f& ***!
  \************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ListingsMap_vue_vue_type_template_id_52f93b7f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ListingsMap.vue?vue&type=template&id=52f93b7f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Map/ListingsMap.vue?vue&type=template&id=52f93b7f&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ListingsMap_vue_vue_type_template_id_52f93b7f___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ListingsMap_vue_vue_type_template_id_52f93b7f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);
//# sourceMappingURL=0.js.map