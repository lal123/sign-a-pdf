<?php

header("Content-Type: text/css");

?>
body {
	background-color: #c0c0c0 !important;
}

.navbar.dark-cyan {
 	background-color: #183034;	
}

.navbar a.navbar-brand {
	min-width: 175px;
}

.btn {
	cursor: pointer;
}

.btn.normalized {
	min-width: 98px;

}
.btn.dark-cyan {
//    box-shadow: 2px 2px 6px #6f7f7f;
//    margin: 10px;
	--bs-btn-focus-shadow-rgb: rgb(128, 192, 192);
}

.form-group input {
	//background-color: none;
}

.btn.dark-cyan, .btn.dark-cyan:visited {
	background-color: #204444;
	border-color: #204444;
}

.btn.dark-cyan:hover, .btn.dark-cyan:active:focus, .btn.dark-cyan:focus {
	background-color: #284c4c;
	border-color: #284c4c;
	outline: none;	
}

.btn.dark-cyan.disabled {
	background-color: rgb(108, 117, 125);
	border-color: rgb(108, 117, 125);
}

.form-panel {
	display: none;
}

.form-panel.showed {
	display: block;
}

.sign-step2-preview {
	max-width: 100%;
}

.page-container {
	position: relative;
}

.page-content {
	display: inline-block;
	position: relative;
	margin: 4px;
	width: auto;
}

.page-preview {
	max-width: 100%;
	//margin: 4px;
    box-shadow: 2px 2px 6px #6f7f7f;
}

#doc-container {
	padding-bottom: 120px;
}

#doc-container #nav-bar {
	position: fixed;
	cursor: move;
	bottom: 75px;
	left: 20px;
	width: 250px;
	height: 40px;
	background-color: #204444;
	border-radius: 20px;
	color: #ffffff;
	padding: 6px 16px 6px 16px;
	font-size: 20px;
	line-height: 28px;
	opacity: 0.75;
}

#doc-container #nav-bar:hover {
	opacity: 0.9;
}

#doc-container #nav-bar select.act {
	color: #ffffff;
	background-color: #204444;
	outline: none;
}

#doc-container #nav-bar a.act {
	cursor: pointer;
	color: #ffffff;
}

#doc-container #nav-bar a.act.small {
	font-size: 17px;
}

.modal-content {
	background-color: #c0c0c0 !important;	
}

.modal-body {
	min-height: 120px;
	max-height: 336px;
}}

.modal-content {
	background-color: #c0c0c0 !important;	
}

a.common {
	color: inherit;
	text-decoration: none;
	border-bottom: dotted 1px rgb(33, 37, 41);
}

.form-check-input.is-valid~.form-check-label a.common{
	border-bottom: dotted 1px #198754;
}}

a.common:hover {
//	color: #385c5c;
}

.doc-small-preview {
	position: relative;
	padding: 0px 4px 16px 4px;
}

.doc-small-preview > .doc-preview {
	display: inline-block;
	position: relative;
	//width: 100%;
	//height: 100%;
}

.doc-small-preview > .doc-name {
    max-width: 100%;
    margin: 0px 10px 0px 10px;
    text-align: center;
    height: 28px;
    line-height: 24px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

.doc-small-preview > .doc-date {
    text-align: center;
	color: #404040;
}

.doc-small-preview > .doc-suppr {
	position: absolute;
	right: -2px;
	top: -2px;
}

.doc-small-preview:hover > .doc-suppr {
}

.doc-small-preview > .doc-suppr > .act {
	font-size: 20px;
	line-height: 20px;
	color: #cc3333;
	cursor: pointer;
	width: 20px;
	height: 20px;
}

.doc-small-preview > .doc-down {
	position: absolute;
	right: -2px;
	top: 20px;
}

.doc-small-preview > .doc-down > .act {
	font-size: 20px;
	line-height: 20px;
	color: #208020;
	cursor: pointer;
	width: 20px;
	height: 20px;
}

.sign-small-preview {
	position: relative;
	padding: 0px 4px 16px 4px;
}

.sign-small-preview > .sign-preview {
	display: inline-block;
	position: relative;
	//width: 100%;
	//height: 100%;
}

.sign-small-preview > .sign-name {
    margin: 0px 10px 0px 10px;
    text-align: center;
    height: 28px;
    line-height: 24px;
}

.sign-small-preview > .sign-date {
    text-align: center;
	color: #404040;
}

.sign-small-preview > .sign-suppr {
	position: absolute;
	right: -2px;
	top: -2px;
}

.sign-small-preview > .sign-suppr > .act {
	font-size: 20px;
	line-height: 20px;
	color: #cc3333;
	cursor: pointer;
	width: 20px;
	height: 20px;
}

.sign-small-preview > .sign-down {
	position: absolute;
	right: -2px;
	top: 20px;
}

.sign-small-preview > .sign-down > .act {
	font-size: 20px;
	line-height: 20px;
	color: #208020;
	cursor: pointer;
	width: 20px;
	height: 20px;
}

.sign-preview {
	max-width: 100%;
	padding: 8px;
	//margin: 4px;
    box-shadow: 2px 2px 6px #6f7f7f;
}

.sign-preview > .sign-img-preview {
	max-width: 100%;
}

.prev-sign-preview {
	max-width: 100%;
	max-height: 100px;
	cursor: pointer;
}

.prev-sign-preview > img {
	max-width: 100%;
	max-height: 100px;
}

.sign-list-item {
	border: dotted 1px #999999;
	padding: 4px 6px 6px 30px;
	border-radius: 12px;
}

#signPreview {
	display: none;
	background-color: transparent;
	background-repeat: no-repeat;
	background-position: 0px 0px;
	background-size: 100% 100%;
	border: none;
	outline: 1px dashed #000000;
	width: 200px;
	height: 100px;
	position: absolute;
	bottom: 50px;
	right: 50px;
	padding: 0px;
	cursor: move;
}

#signPreview .sign_cmd_bar {
    display: block;
    position: absolute;
    padding: 0px;
    width: 28px;
    height: 50px;
    right: -28px;
    top: 2px;
}

#signPreview .helper {
    display: block;
    position: absolute;
    padding: 0px;
    width: 10px;
    height: 10px;
    border: solid 1px black;
    border-radius: 10px;
    background-color: white;
 }

#signPreview .helper.ne {
    right: -5px;
    top: -5px;
} 

#signPreview .helper.nw {
    left: -5px;
    top: -5px;
} 

#signPreview .helper.sw {
    left: -5px;
    bottom: -5px;
} 

#signPreview .helper.se {
    right: -5px;
    bottom: -5px;
} 

.sign_cmd_bar > span {
	display: block;
	position: relative;
	width: 100%;
	height: 100%;
}

.sign_cmd_bar .close {
	position: absolute;
	right: 0px;
	top: 0px;
	font-size: 24px;
	line-height: 24px;
	color: #cc3333;
	cursor: pointer;
	width: 24px;
	height: 24px;
}

.sign_cmd_bar .check {
	position: absolute;
	right: 0px;
	top: 26px;
	font-size: 24px;
	line-height: 24px;
	color: #208020;
	cursor: pointer;
	width: 24px;
	height: 24px;
}

#sign_toolbar .btn {
	min-width: 141px;
}

.navbar-text.welcome {
	margin: 0px 10px 0px 5px;
}

.sign-container {
	position: relative;
	width: 490px;
	max-width: 100%;
	height: 200px;
	padding-right: 18px;
}

.sign-canvas {
	left: 0px;
	top: 0px;
	width: 490px;
	max-width: 100%;
	height: 200px;
	outline: 1px dotted #000000;
}

.sign-container > .clear-canvas {
	position: absolute;
	right: -10px;
	top: 0px;
}

.sign-container > .clear-canvas > .act {
	font-size: 24px;
	line-height: 24px;
	color: #204444;
	cursor: pointer;
	width: 24px;
	height: 24px;
}

.text-color-holder {
	width: 60px;
	height: 40px;
	padding: 0px;
	margin: 4px 0px 0px 2px;
}

.text-color-preview {
	font-size: 28px;
	line-height: 34px;
}

#signText {
	max-width: 140px;
}

#textFont {
	max-width: 140px;
}

#textFontChoice {
	text-decoration: none;
	color: #000000;
}

#textFontChoice:active, #textFontChoice:focus {
	outline: none;
}

#textFontPreview {
	width: 136px;
	height: 36px;
	color: #000000;
	padding: 2px 12px 2px 12px;
	border: solid 1px rgb(222, 226, 230);
	border-radius: 6px;
	background-color: #ffffff;
	margin: 0px 0px 0px 0px;
	overflow: hidden;
}

#textFontChoice:active > #textFontPreview, #textFontChoice:focus > #textFontPreview {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 .25rem rgba(13,110,253,.25);
}

#textFontList {
	display: none;
    position: absolute;
    border: solid 1px #767676;
    padding: 0px 0px 0px 0px;
	width: 136px;
	height: 196px;
    right: 10px;
    top: 10px;
    background-color: #ffffff;
    overflow: hidden;
    overflow-y: scroll;
}

#textFontList > .fontItem {
	height: 36px;
	cursor: pointer;
    padding: 2px 12px 2px 12px;
    overflow: hidden;
}

#colorpicker {
    display: none;
    position: absolute;
    right: 10px;
    top: 10px;
    width: 200px;
    height: 200px;
    background-color: #204444;
    //border: solid 1px #204444;
    padding: 1px;
    border-radius: 16px;
}

#colorpicker.top:before {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -8px;
  width: 0;
  height: 0;
  border-top: 8px solid #204444;
  border-right: 8px solid transparent;
  border-left: 8px solid transparent;
}

#colorpicker.top:after {
  content: '';
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -8px;
  width: 0;
  height: 0;
  border-top: 8px solid #204444;
  border-right: 8px solid transparent;
  border-left: 8px solid transparent;
}

#colorpicker.right:before {
  content: '';
  position: absolute;
  right: 100%;
  top: 50%;
  margin-top: -8px;
  width: 0;
  height: 0;
  border-right: 8px solid #204444;
  border-bottom: 8px solid transparent;
  border-top: 8px solid transparent;
}

#colorpicker.right:after {
  content: '';
  position: absolute;
  right: 100%;
  top: 50%;
  margin-top: -8px;
  width: 0;
  height: 0;
  border-right: 8px solid #204444;
  border-bottom: 8px solid transparent;
  border-top: 8px solid transparent;
}

#modal-progress-bar {
    -webkit-transition: none;
    -moz-transition: none;
    -ms-transition: none;
    -o-transition: none;
    transition: none;
}

@media screen and (max-width: 1060px) and (min-width: 980px) {
	.nav-link.reduced {
		max-width: 135px;
	    text-overflow: ellipsis;
	    overflow: hidden;
	    white-space: nowrap;
	}
	.navbar-item.welcome {
		max-width: 135px;
	    text-overflow: ellipsis;
	    overflow: hidden;
	    white-space: nowrap;
	}
}

@media screen and (max-width: 600px) {

	#sign_toolbar .btn {
		font-size: 16px;
		line-height: 24px;
		min-width: 120px !important;
		max-width: 120px !important;
	}
}

@media screen and (max-width: 450px) {

	#sign_toolbar .btn-group {
		font-size: 12px;
		line-height: 18px;
		min-width: 90px !important;
		max-width: 90px !important;
	}

	#sign_toolbar .btn {
		font-size: 12px;
		line-height: 18px;
		min-width: 90px !important;
		max-width: 90px !important;
		padding: 8px 8px 8px 8px !important;
	}

	.modal-footer .btn {
		font-size: 12px;
		line-height: 18px;
		min-width: 90px !important;
		max-width: 90px !important;
		padding: 8px 8px 8px 8px !important;
	}

	.nav-link.limited {
		max-width: 135px;
	    text-overflow: ellipsis;
	    overflow: hidden;
	    white-space: nowrap;
	}
}

@media screen and (max-width: 520px) {

	#signText {
		max-width: 85px;
	}

	#textFont {
		max-width: 85px;
	}

	#textFontPreview {
		max-width: 85px;
	}
}

