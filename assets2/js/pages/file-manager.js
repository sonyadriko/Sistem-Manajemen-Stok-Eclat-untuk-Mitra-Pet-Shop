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

/***/ "./app/assets/es6/pages/file-manager.js":
/*!**********************************************!*\
  !*** ./app/assets/es6/pages/file-manager.js ***!
  \**********************************************/
/***/ (() => {

eval("class FileManager {\r\n\r\n    static init() {\r\n\r\n        const hide = 'd-none'\r\n        const fileItems = '.file-manager-content-files .file';\r\n        const fileDetail = '.file-manager-content-details .content-details';\r\n        const fileContentDetails = '.file-manager-content-details'\r\n        const fileDetailNoData = '.file-manager-content-details .content-details-no-data';\r\n\r\n        function showFileDetails () {\r\n            $(fileDetailNoData).addClass(hide)\r\n            $(fileDetail).removeClass(hide)\r\n        }\r\n\r\n        function hideFileDetails () {\r\n            $(fileDetailNoData).removeClass(hide)\r\n            $(fileDetail).addClass(hide)\r\n        }\r\n\r\n        $(fileItems).on('click', (e) => {\r\n            showFileDetails()\r\n            $(fileItems).removeClass('active')\r\n            $(e.currentTarget).addClass('active')\r\n            $(fileContentDetails).addClass('details-open')\r\n        })\r\n\r\n        $('.unselect-bg').on('click', (e) => {\r\n            hideFileDetails()\r\n            $(fileItems).removeClass('active')\r\n        })\r\n\r\n        $('.content-details-close a').on('click', (e) => {\r\n            $(fileContentDetails).removeClass('details-open')\r\n        })\r\n\r\n        $('#open-manager-menu').on('click', (e) => {\r\n            $('.file-manager-nav').addClass('nav-open')\r\n            $(e.currentTarget).addClass(hide)\r\n            $('#close-manager-menu').removeClass(hide)\r\n        })\r\n\r\n        $('#close-manager-menu').on('click', (e) => {\r\n            $('.file-manager-nav').removeClass('nav-open')\r\n            $(e.currentTarget).addClass(hide)\r\n            $('#open-manager-menu').removeClass(hide)\r\n        })\r\n    }\r\n}\r\n\r\n$(() => { FileManager.init(); });\r\n\r\n\n\n//# sourceURL=webpack://enlink/./app/assets/es6/pages/file-manager.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./app/assets/es6/pages/file-manager.js"]();
/******/ 	
/******/ })()
;