(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./resources/js/config/store.js":
/*!**************************************!*\
  !*** ./resources/js/config/store.js ***!
  \**************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");

var store = new vuex__WEBPACK_IMPORTED_MODULE_0__["default"].Store({
  state: {
    count: 0,
    listings: [],
    listing: {},
    search: {}
  },
  getters: {
    getListingById: function getListingById(store) {
      return function (id) {
        return state.listings.find(function (listing) {
          return listing.id === id;
        });
      };
    },
    savedListings: function savedListings(store) {
      return store.listings.filter(function (listing) {
        return listing.saved;
      });
    },
    savedListingsCount: function savedListingsCount(store, getters) {
      return getters.savedListings.count;
    }
  },
  mutations: {
    initialiseStore: function initialiseStore(state) {
      // Check if the ID exists
      if (localStorage.getItem('store')) {
        // Replace the state object with the stored item
        this.replaceState(Object.assign(state, JSON.parse(localStorage.getItem('store'))));
      }
    },
    listing: function listing(state, _listing) {
      state.listing = _listing;
    },
    listings: function listings(state, _listings) {
      state.listings = _listings;
    },
    search: function search(state, _search) {
      state.search = _search;
    }
  }
});
store.subscribe(function (mutation, state) {
  // Store the state object as a JSON string
  localStorage.setItem('store', JSON.stringify(state));
});
/* harmony default export */ __webpack_exports__["default"] = (store);

/***/ })

}]);
//# sourceMappingURL=1.js.map