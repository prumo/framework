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

require_once dirname(__DIR__).'/prumo.php';
pProtect('prumo_groups');

require_once __DIR__.'/ctrl_groups.php';
?>

<fieldset>
<legend><?=_('Grupos de Usuários');?></legend>

<div id="crudGroups_form">
    <br />
    <table class="prumoFormTable">
        <tr>
            <td class="prumoFormLabel"><?=_('Nome do grupo');?>:</td>
            <td class="prumoFormFields"><input id="groupname" type="text" size=45 maxlength="30" autofocus="autofocus" />*</td>
        </tr>
        <tr>
            <td class="prumoFormLabel"></td>
            <td class="prumoFormFields"><input id="enabled" type="checkbox" checked="checked" /> <?=_('Ativo');?></td>
        </tr>
        <tr>
            <td class="prumoFormLabel"></td>
            <td class="prumoFormFields"><?php $crudGroups->drawControls();?></td>
        </tr>
    </table>
    
    <br />
    * <?=_('campos de preenchimento obrigatório');?>
</div>

<?php 
$crudGroups->drawCrudList(); 
?>

</fieldset>
