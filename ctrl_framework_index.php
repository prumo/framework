<?php
/**
 * Copyright (c) 2010 Emerson Casas Salvador <salvaemerson@gmail.com> e Odair Rubleski <orubleski@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the “Software”), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 * 
 * THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * Este arquivo controla a interface do sistema
 */

require_once __DIR__.'/view_header.php';
    
$pLogin = new PrumoLogin($GLOBALS['pConfig']['appIdent'], '', '');

if (isset($_GET['action']) && $_GET['action'] == 'logoff') {
    
    $pLogin->logoff();
    pRedirect($GLOBALS['pConfig']['appWebPath'].'/index.php');
} else {
    
    if ($pLogin->isSession()) {
        
        if ($GLOBALS['pConfig']['afterLogin'] == 'index.php') {
            
            include __DIR__.'/view_loading.php';
            include __DIR__.'/view_date_calculator.php';
            include __DIR__.'/view_page.php';
            include __DIR__.'/view_footer.php';
        } else {
            
            if (empty($GLOBALS['pConfig']['appWebPath']) || $GLOBALS['pConfig']['appWebPath'] == '/') {
                pRedirect($GLOBALS['pConfig']['afterLogin']);
            } else {
                pRedirect($GLOBALS['pConfig']['appWebPath'].'/'.$GLOBALS['pConfig']['afterLogin']);
            }
        }
    } else {
        include __DIR__.'/view_login.php';
    }
}
