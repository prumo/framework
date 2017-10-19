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

require_once 'prumo.php';
require_once $GLOBALS['pConfig']['prumoPath'].'/ctrl_connection_admin.php';
require_once $GLOBALS['pConfig']['prumoPath'].'/ctrl_connection.php';

pProtect('prumo_devtools');

// verifica se existe a pasta de scripts de atualização do banco da aplicação
$scriptDir = $GLOBALS['pConfig']['appPath'].'/updatedb';

if (! is_writable($GLOBALS['pConfig']['appPath']) and !file_exists($scriptDir)) {
    
    $msg = _('Diretório "%dir%" não possui permissão de escrita, nada feito!');
    echo str_replace('%dir%', $GLOBALS['pConfig']['appPath'], $msg);
    
    exit;
}

if (! file_exists($scriptDir)) {
    
    if (! mkdir($scriptDir)) {
        
        $msg = _('Erro ao criar o diretório "%dir%", nada feito!');
        echo str_replace('%dir%', $GLOBALS['pConfig']['appPath'], $msg);
        
        exit;
    }
}

//escolhe o nome do arquivo
$fileName = $pConnectionPrumo->sqlQuery('SELECT now()');
$fileName = str_replace(':','',$fileName);
$fileName = str_replace('-','',$fileName);
$fileName = str_replace('.','',$fileName);
$fileName = str_replace(' ','',$fileName);
$fileName .= '.php';

$completeFileName = $scriptDir.'/'.$fileName;

$fileContent  = '<?php'."\n";
$fileContent .= '$sql = \'BEGIN;'."\n\n".str_replace("'", "\'", $_POST['ddl'])."\n\nCOMMIT;".'\';';
$fileContent .= "\n";

if (file_put_contents($completeFileName, $fileContent) === false) {
    
    $msg = _('Erro ao gravar o arquivo "%file%"!');
    echo str_replace('%file%', $completeFileName, $msg);
    
    exit;
}

//verifica se o schema existe na base de dados, caso não existe, cria
$schema = $pConnectionPrumo->sqlQuery('SELECT count(*) FROM information_schema.schemata WHERE schema_name='.pFormatSql($GLOBALS['pConfig']['loginSchema_prumo'], 'string').';');
if (! $schema) {
    $pConnectionPrumo->sqlQuery('CREATE SCHEMA '.$GLOBALS['pConfig']['loginSchema_prumo'].';');
}

if ($_POST['uptodate'] == 't') {
    writeAppUpdate($fileName);
}

echo 'OK';

