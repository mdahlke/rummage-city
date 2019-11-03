(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./resources/js/config/router.js":
/*!***************************************!*\
  !*** ./resources/js/config/router.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var router = new VueRouter({
  mode: 'history',
  routes: [{
    path: '/listings',
    name: 'listings',
    component: Listings,
    children: [// Here we specify that the `ProductImagePopup`
    // component should be rendered as a nested
    // route of the `Product` component.
    {
      path: ':id/view',
      name: 'listing.view',
      component: ListingView
    }]
  }, {
    path: '/listings/:location',
    name: 'listings.location',
    component: Listings
  }]
});
/* harmony default export */ __webpack_exports__["default"] = (router);

/***/ })

}]);
//# sourceMappingURL=0.js.map