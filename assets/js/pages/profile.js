/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./app/assets/es6/pages/profile.js":
/*!*****************************************!*\
  !*** ./app/assets/es6/pages/profile.js ***!
  \*****************************************/
/***/ (() => {

eval("class PagesProfile {\r\n\r\n    static init() {\r\n\r\n        $('#list-view-btn').on('click', (e) => {\r\n            $('#list-view').removeClass('d-none');\r\n            $('#card-view').addClass('d-none')\r\n            $(e.currentTarget).addClass('active');\r\n            $('#card-view-btn').removeClass('active');\r\n        })\r\n\r\n        $('#card-view-btn').on('click', (e) => {\r\n            $('#card-view').removeClass('d-none');\r\n            $('#list-view').addClass('d-none');\r\n            $(e.currentTarget).addClass('active');\r\n            $('#list-view-btn').removeClass('active');\r\n        })\r\n    }\r\n}\r\n\r\n$(() => { PagesProfile.init(); });\r\n\r\n\n\n//# sourceURL=webpack://enlink/./app/assets/es6/pages/profile.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./app/assets/es6/pages/profile.js"]();
/******/ 	
/******/ })()
;