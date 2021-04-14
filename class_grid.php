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
 * PrumoGrid é o grid usado no PrumoSearch, PrumoCrudList e PrumoQueue
 */
class PrumoGrid
{
    private $parentName;
    private $jsName;
    private $htmlId;
    private $lines;
    private $column;
    public $width;
    public $ind = '';
    public $lineEventOnData;
    public $pointerCursorOnData;
    public $fieldNameMarkNew = '';
    
    /**
     * Construtos da calsse PrumoGrid
     *
     * @param $parentName string: nome do objeto pai
     * @param $lines integer: quantidade de linhas do grid
     */
    function __construct(string $parentName, int $lines)
    {
        $this->parentName = $parentName;
        $this->jsName = "$parentName.pGrid";
        $this->htmlId = str_replace('.', '_', $this->jsName);
        $this->lines = $lines;
        $column = array();
        $this->width = 0;
        $this->lineEventOnData = false;
        $this->pointerCursorOnData = '';
    }
    
    /**
     * Adiciona uma coluna no GRID
     *
     * @param $params string: array com os parametros da coluna
     */
    public function addColumn(string $params)
    {
        $param = pParameters($params);
        $name = $param['name'];
        
        $label = isset($param['label']) ? $param['label'] : $param['name'];
        $labelAlign = isset($param['labelalign']) ? $param['labelalign'] : 'center';
        $type = isset($param['type']) ? $param['type'] : 'string';
        
        if (isset($param['align']) && ! empty($param['align'])) {
            $align = $param['align'];
        } else {
            if (in_array($type, array('numeric', 'money'))) {
                $align = 'right';
            } else if (in_array($type, array('integer', 'serial', 'date', 'time', 'timestamp', 'boolean', 'phone'))) {
                $align = 'center';
            } else {
                $align = 'left';
            }
        }
        
        //visible - default true
        if (! isset($param['visible'])) {
            $visible = true;
        } else {
            $visible = $param['visible'] == 'false' ? false : true;
        }
        
        $this->column[] = array(
            'name' => $name,
            'label' => $label,
            'labelalign' => $labelAlign,
            'align' => $align,
            'visible' => $visible
        );
    }
    
    /**
     * Gera o código de início da tabela
     *
     * @return string: código HTML
     */
    private function tableGridHeader() : string
    {
        return "\n{$this->ind}<table id=\"{$this->htmlId}\" class=\"prumoGridTable\">\n";
    }
    
    /**
     * Gera o código do fechamento da tabela
     *
     * @return string: código HTML
     */
    private function tableClose() : string
    {
        return "{$this->ind}</table>\n";
    }
    
    /**
     * Gera o código do cabecalho da tabela
     *
     * @return string: código HTML
     */
    private function dataHeader() : string
    {
        $header = "{$this->ind}\t<tr class=\"prumoGridTh\">\n";
        
        for ($i = 0; $i < count($this->column); $i++) {
            
            if ($this->column[$i]['visible'] != false) {
                $header .= "{$this->ind}\t\t<th class=\"prumoGridTh\" id=\"PrumoGridTh_{$this->parentName}_{$this->column[$i]['name']}\" style=\"text-align:{$this->column[$i]['labelalign']}"
                                                              ."\" onclick=\"{$this->parentName}.sort('{$this->column[$i]['name']}')\">{$this->column[$i]['label']}</th>\n";
            }
        }
        
        $header .= "{$this->ind}\t</tr>\n";
        
        return $header;
    }
    
    /**
     * Gera o código de cada linha da tabela
     *
     * @param $index integer: indice da linha
     *
     * @return string: código HTML
     */
    private function line(int $index) : string
    {
        $line = is_int($index/2) ? "{$this->ind}\t<tr class=\"prumoGridTrEven\">\n" : "{$this->ind}\t<tr class=\"prumoGridTrOdd\">\n";
        
        for ($i = 0; $i < count($this->column); $i++) {
            
            if ($this->column[$i]['visible'] != false) {
                $line .= "{$this->ind}\t\t<td class=\"prumoGridTd\" style=\"text-align:{$this->column[$i]['align']}\"><br /></td>\n";
            }
        }
        
        $line .= "{$this->ind}\t</tr>\n";
        
        return $line;
    }
    
    /**
     * Gera o código de todas as linhas
     *
     * @return string: código HTML
     */
    private function lines() : string
    {
        $lines = '';
        
        for ($i = 0; $i < $this->lines; $i++) {
            $lines .= $this->line($i);
        }
        
        return $lines;
    }
    
    /**
     * Gera o código JS do grid no lado do cliente
     *
     * @return string: código JS
     */
    private function clientObject() : string
    {
        $client = <<<HTML
        {$this->ind}<script type=text/javascript>
        {$this->ind}    {$this->jsName} = new PrumoGrid('{$this->jsName}');
        {$this->ind}    {$this->jsName}.lines = {$this->lines};
        {$this->ind}    {$this->jsName}.fieldNameMarkNew = '{$this->fieldNameMarkNew}';
        {$this->ind}    document.pGrid.push({$this->jsName});
        {$this->ind}</script>
        
        HTML;
        
        return $client;
    }
    
    /**
     * Gera o código completo com HTML e JS
     *
     * @param $verbose boolean: quando true imprime o código gerado
     *
     * @return string: código HTML e JS
     */
    public function draw(bool $verbose) : string
    {
        $htmlGrid  = $this->tableGridHeader();
        $htmlGrid .= $this->dataHeader();
        $htmlGrid .= $this->lines();
        $htmlGrid .= $this->tableClose();
        $htmlGrid .= $this->clientObject();
        
        if ($verbose) {
            echo $htmlGrid;
        }
        
        return $htmlGrid;
    }
}
