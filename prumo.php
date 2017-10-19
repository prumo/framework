<?php

/* *********************************************************************
 *
 *  Prumo Framework para PHP é um framework vertical para
 *  desenvolvimento rápido de sistemas de informação web.
 *  Copyright (C) 2010 Emerson Casas Salvador <salvaemerson@gmail.com>
 *  e Odair Rubleski <orubleski@gmail.com>
 *
 *  This file is part of Prumo.
 *
 *  Prumo is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3, or (at your option)
 *  any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 * ******************************************************************* */

$pConfig['appPath'] = isset($_SERVER['REMOTE_ADDR']) ? substr(dirname($_SERVER['SCRIPT_FILENAME']), 0, strlen('/prumo')*-1) : substr(getcwd(), 0, strlen('/prumo')*-1);
$pConfig['appWebPath'] = isset($_SERVER['REMOTE_ADDR']) ? substr(dirname($_SERVER['SCRIPT_NAME']), 0, strlen('/prumo')*-1) : '';

if (file_exists($GLOBALS['pConfig']['appPath'].'/prumo.php')) {
    require_once $GLOBALS['pConfig']['appPath'].'/prumo.php';
    require_once $GLOBALS['pConfig']['appPath'].'/prumo/ctrl_ambient.php';
}

