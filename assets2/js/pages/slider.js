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

/***/ "./app/assets/es6/pages/slider.js":
/*!****************************************!*\
  !*** ./app/assets/es6/pages/slider.js ***!
  \****************************************/
/***/ (() => {

eval("class ComponentSlider {\r\n\r\n    static init() {\r\n        const horizonPrimary = document.getElementById('horizon-primary');\r\n        const verticalPrimary = document.getElementById('vertical-default');\r\n        const rangeSlider = document.getElementById('range-slider');\r\n        const stepSlider = document.getElementById('step-slider');\r\n\r\n        noUiSlider.create(horizonPrimary, {\r\n            start: 60,\r\n            connect: \"lower\",\r\n            step: 1,\r\n            range: {\r\n                'min': 0,\r\n                'max': 100\r\n            }\r\n        });\r\n\r\n        noUiSlider.create(verticalPrimary, {\r\n            start: 60,\r\n            connect: \"lower\",\r\n            orientation: 'vertical',\r\n            step: 1,\r\n            range: {\r\n                'min': 0,\r\n                'max': 100\r\n            }\r\n        });\r\n\r\n        noUiSlider.create(stepSlider, {\r\n            start: 20,\r\n            connect: \"lower\",\r\n            range: {\r\n                min: 0,\r\n                max: 100\r\n            },\r\n            pips: {\r\n                mode: 'values',\r\n                values: [0,10,20,30,40,50,60,70,80,90,100],\r\n                density: 10\r\n            }\r\n        });\r\n\r\n        noUiSlider.create(rangeSlider, {\r\n            connect: true,\r\n            behaviour: 'tap',\r\n            start: [ 300, 800 ],\r\n            range: {\r\n                'min': [ 0 ],\r\n                'max': [ 1000 ]\r\n            }\r\n        });\r\n\r\n        const nodes = [\r\n            document.getElementById('range-min'), // 0\r\n            document.getElementById('range-max')  // 1\r\n        ];\r\n\r\n        // Display the slider value and how far the handle moved\r\n        // from the left edge of the slider.\r\n        rangeSlider.noUiSlider.on('update', function ( values, handle, unencoded, isTap, positions ) {\r\n            nodes[handle].innerHTML = '$' + values[handle] ;\r\n        });\r\n    }\r\n}\r\n\r\n$(() => { ComponentSlider.init(); });\r\n\r\n\n\n//# sourceURL=webpack://enlink/./app/assets/es6/pages/slider.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./app/assets/es6/pages/slider.js"]();
/******/ 	
/******/ })()
;