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
	width: auto;
}

.page-preview {
	max-width: 100%;
	margin: 4px;
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

.doc-small-preview > .doc-name {
    max-width: 100%;
    height: 24px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

.doc-small-preview > .doc-date {
	color: #404040;
}

.doc-small-preview > .doc-suppr {
	position: absolute;
	right: 4px;
	top: 0px;
//	opacity: 0.75
}

.doc-small-preview:hover > .doc-suppr {
//	opacity: 1.0;
}

.doc-small-preview > .doc-suppr > .doc-suppr-btn {
	width: 20px;
	height: 20px;
	padding: 0px;
	line-height: 15px;
	font-size: 15px;
}

@media screen and (min-width: 991px) {
}

#signPreview {
	display: none;
}

.resizable {
  background-color: transparent;
  background-repeat: no-repeat;
  background-position: 0px 0px;
  background-size: 100% 100%;
  width: 200px;
  height: 100px;
  position: absolute;
  bottom: 50px;
  right: 50px;
}

.resizable .resizers{
  width: 100%;
  height: 100%;
  border: 1px dotted #000000;
  box-sizing: border-box;
}

.resizable .resizers .resizer{
  width: 10px;
  height: 10px;
  border-radius: 50%; /*magic to turn square into circle*/
  background: white;
  border: 1px solid #000000;
  position: absolute;
}

.resizable .resizers .resizer.top-left {
  left: -5px;
  top: -5px;
  cursor: nwse-resize; /*resizer cursor*/
}
.resizable .resizers .resizer.top-right {
  right: -5px;
  top: -5px;
  cursor: nesw-resize;
}
.resizable .resizers .resizer.bottom-left {
  left: -5px;
  bottom: -5px;
  cursor: nesw-resize;
}
.resizable .resizers .resizer.bottom-right {
  right: -5px;
  bottom: -5px;
  cursor: nwse-resize;
}
