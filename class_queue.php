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
 * PrumoQueue é um tipo de lista parecida com o PrumoCrudList, porém não precisa estar associado a um objeto crud,
 * pode ser usado combinado com PrumoTabSet.
 *
 * Serve para fazer telas com filas de trabalho na aplicação
 */
class PrumoQueue extends PrumoSearch
{
    
    protected $orderby;
    
    public $htmlTop = '';
    public $htmlBottom = '';
    
    /**
     * Adiciona um container ao código, sendo o id o nome do objeto
     *
     * @param $pSearch string: código de entrada
     *
     * @return string: código de saída
     */
    protected function addWindow($pSearch)
    {
        $pQueue  = '<div id="div_'.$this->name.'">';
        $pQueue .= $pSearch;
        $pQueue .= '</div>';
        
        return $pQueue;
    }
    
    /**
     * Gera o código completo
     *
     * @param $verbose boolean: quando true imprime o código gerado
     *
     * @return string: código gerado
     */
    public function draw($verbose)
    {
        // junta os objetos
        $pSearchInit = $this->initClientObject();
        $pSearchChilds = '<div id="' . $this->name . '_header">' . $this->htmlTop . '</div>';
        $pSearchChilds .= '<div id="' . $this->name . '_body">';
        $pSearchChilds .= parent::makeFilters();
        $pSearchChilds .= parent::makeGrid();
        $pSearchChilds .= parent::makeGridNavigation();
        $pSearchChilds .= '</div>';
        $pSearchChilds .= '<div id="' . $this->name . '_footer">' . $this->htmlBottom . '</div>';
        $pSearchChilds = $this->addWindow($pSearchChilds);
        
        // muda o evento click
        $pQueue = "\n";
        $pQueue .= '<script type="text/javascript">'."\n";
        $pQueue .= '    pGrid_'.$this->name.'.lineEventOnData = \''.$this->name.'.lineClick(%)\';'."\n";
        $pQueue .= '</script>'."\n";
        
        $pSearch = $pSearchInit . $pSearchChilds . $pQueue;
        
        if ($verbose) {
            echo $pSearch;
        }
        
        return $pSearch;
    }
    
    /**
     * Gera um jsvascript que chama o goSearch no lado do cliente
     */
    public function goSearch()
    {
        echo '<script type="text/javascript">'.$this->name.'.goSearch(1);</script>'."\n";
    }
    
    /**
     * Adiciona um filtro na pesquisa
     *
     * @param $field string: nome do campo
     * @param $operator string: operador lógico
     * @param $value string: valor do campo
     * @param $visible boolean: indica se o filtro deve aparecer na view
     */
    public function addFilter($field, $operator, $value, $visible)
    {
        $booVisible = $visible == false ? 'false' : 'true';
        
        echo '<script type="text/javascript">'."\n";
        echo '    '.$this->name.'.pFilter.addFilter(null,\''.$field.'\',\''.$operator.'\',\''.$value.'\',\'\','.$booVisible.');'."\n";
        echo '    '.$this->name.'.pFilter.draw();'."\n";
        echo '</script>'."\n";
    }
    
    /**
     * Inicializa o objeto no lado do cliente
     *
     * @return string: código gerado
     */
    protected function initClientObject()
    {
        // instancia o objeto PrumoSearch no cliente
        $clientObject = $this->ind. '<script type="text/javascript">'."\n";
        $clientObject .= $this->ind. '    '.$this->name.' = new PrumoQueue(\''.$this->name.'\',\''.$this->ajaxFile.'\');'."\n";
        
        // repassa condicionalmente o pog debug para o objeto ajax
        if (isset($this->param['debug']) && $this->param['debug']) {
            $clientObject .= $this->ind. '    '.$this->name.'.pAjax.debug = true;'."\n";
        }
        
        $clientObject .= $this->ind. '</script>'."\n";
        
        return $clientObject;
    }
    
    /**
     * Adiciona um campo
     *
     * @param $params array: array com os parâmetros do campo
     */
    public function addField($params)
    {
        parent::addField($params);
        
        $param = pParameters($params);
        if (! isset($this->param['priorityfield']) or $this->param['priorityfield'] == '') {
            $this->param['priorityfield'] = $param['name'];
        }
    }
    
    /**
     * Define o campo de ordenação da consulta
     *
     * @param $orderby string: nome do campo de ordenação
     */
    public function setOrderby($orderby)
    {
        $this->orderby = $orderby;
    }
    
    /**
     * Gera o código SQL da ordenação da consulta
     *
     * @return string: SQL da ordenação da consulta
     */
    public function sqlOrderby()
    {
        return $this->orderby == '' ? ' ORDER BY ' .$this->param['priorityfield']: ' ORDER BY ' .$this->param['priorityfield'] . ',' . $this->orderby;
    }
}
