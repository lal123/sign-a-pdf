<?php

header('Content-Type: text/css');

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

.signSetp2Preview {
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

.modal-content {
	background-color: #c0c0c0 !important;	
}

.modal-body {
	min-height: 120px;
}

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
	padding: 0px 0px 8px 0px;
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
	width: 450px;
	height: 200px;
	border: 1px dotted #000000;
}

.sign-canvas {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 450px;
	height: 200px;
}

.sign-container > .clear-canvas {
	position: absolute;
	right: -28px;
	top: 0px;
}

.sign-container > .clear-canvas > .act {
	font-size: 24px;
	line-height: 24px;
	color: #cc3333;
	cursor: pointer;
	width: 24px;
	height: 24px;
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

@media screen and (max-width: 450px) {
	.nav-link.limited {
		max-width: 135px;
	    text-overflow: ellipsis;
	    overflow: hidden;
	    white-space: nowrap;
	}
}

