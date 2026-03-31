<?php

header('Content-Type: text/css');

?>

.lang-select #lang-select-menu {
//    position: absolute;
//    right: 8px;
    top: 36px;
    width: 180px;
    height: 98px;
}

.lang-select .flag{
	padding: 0;
	margin: 1px 2px 2px 1px;
	display: inline-block;
	width: 20px;
	height: 20px;
	cursor: pointer;
  	vertical-align: bottom;
}
.lang-select .flag.flag-en {
	background: url(/img/uk.png) 0px 0px no-repeat;
	background-size: 20px 20px;
	top: 0px;
}
.lang-select .flag.flag-fr {
	background: url(/img/fr.png) 0px 0px no-repeat;
	background-size: 20px 20px;
	top: 0px;
}


@media screen and (min-width: 991px) {
	#menu-right {
		//position: absolute; right: 0px;
	}
}