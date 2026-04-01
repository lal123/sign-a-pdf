<?php

header('Content-Type: text/css');

?>

.navbar a.navbar-brand {
	min-width: 175px;
}

.lang-select #lang-select-menu {
    white-space: nowrap;
    padding: 0px 8px 4px 0px;
}

.lang-select .flag {
	padding: 0;
	margin: 0px 4px 2px 8px;
	display: inline-block;
	width: 20px;
	height: 20px;
	cursor: pointer;
  	vertical-align: bottom;
}
.lang-select .flag.flag-en {
	background: url(/img/uk.png) 0px 0px no-repeat;
	background-size: 20px 20px;
}
.lang-select .flag.flag-fr {
	background: url(/img/fr.png) 0px 0px no-repeat;
	background-size: 20px 20px;
}


@media screen and (min-width: 991px) {
}