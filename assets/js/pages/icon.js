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

/***/ "./app/assets/es6/pages/icon.js":
/*!**************************************!*\
  !*** ./app/assets/es6/pages/icon.js ***!
  \**************************************/
/***/ (() => {

eval("class Icon {\r\n\r\n    static init() {\r\n        function copy(value)  {\r\n            const promise = new Promise((resolve) => {\r\n                let copyTextArea = null;\r\n                try {\r\n                    copyTextArea = document.createElement(\"textarea\");\r\n                    copyTextArea.style.height = '0px';\r\n                    copyTextArea.style.opacity = '0';\r\n                    copyTextArea.style.width = '0px';\r\n                    document.body.appendChild(copyTextArea);\r\n                    copyTextArea.value = value;\r\n                    copyTextArea.select();\r\n                    document.execCommand('copy');\r\n                    resolve(value);\r\n                } finally {\r\n                    if (copyTextArea && copyTextArea.parentNode) {\r\n                        copyTextArea.parentNode.removeChild(copyTextArea);\r\n                    }\r\n                }\r\n            });\r\n    \r\n            return (promise);\r\n        }\r\n\r\n        function showToast() {\r\n            var toastHTML = `<div class=\"toast fade hide\" data-delay=\"1500\">\r\n                <div class=\"toast-body\">\r\n                    <i class=\"anticon anticon-check-o text-success\"></i>\r\n                    <span class=\"ml-1\">Copied</span>\r\n                </div>\r\n            </div>`\r\n        \r\n            $('#notification-toast').append(toastHTML)\r\n            $('#notification-toast .toast').toast('show');\r\n            setTimeout(function(){ \r\n                $('#notification-toast .toast:first-child').remove();\r\n            }, 1500);\r\n        }\r\n\r\n        $('.icons-list li').on('click', (e) => {\r\n            const $this = $(e.currentTarget);\r\n            const copiedString = $this.children('.icon-wrap').html()\r\n            copy(copiedString).then(() => {\r\n                showToast()\r\n            });\r\n        })\r\n    }\r\n    \r\n}\r\n\r\n$(() => { Icon.init(); });\r\n\r\n\n\n//# sourceURL=webpack://enlink/./app/assets/es6/pages/icon.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./app/assets/es6/pages/icon.js"]();
/******/ 	
/******/ })()
;