<?php

/*
 * Builder_Grid
 *
 * Copyright(c) 2010, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * Constructs grids from meta data
 */

class Builder_Grid extends Builder_Base
{
	// Sorting types
	const SORT_ALPHA				= 'alpha';
	const SORT_NUMERIC				= 'numeric';
	
    protected $iColCount;
    protected $iColSpan;
    protected $sGroup;

    protected function ConstructGridData($aMeta)
    {
        $this->iColCount = is_array($aMeta['columns']) ? count($aMeta['columns']) : 0;
        $this->iColSpan = $this->iColCount;
        $this->sGroup = !empty($aMeta['group']) ? $aMeta['group'] : '';
    }

    protected function ConstructFlags($aMeta)
    {
        $aFlags = array('grid' => true);
        foreach ($aMeta['columns'] as $aColumn)
        {
        	#-> Can't sort columns if we're doing groups
            if (!empty($aColumn['sort']) && !$this->sGroup)
            {
                $aFlags['sortable'] = true;
            }

            #-> Determine which data source has the check status of the checkbox 
            if (!empty($aColumn['type']))
            {
                switch ($aColumn['type'])
                {
                    case 'checkbox':
                        $aFlags['checkbox'] = true;
                        break;
                        
                    case 'radio':
                        $aFlags['radio'] = true;
                        break;
                }
            }
        }

        if (!empty($aMeta['paging']))
        {
            $aFlags['paging'] = true;
        }

        return array_keys($aFlags);
    }

    protected function ConstructFlagMeta($aMeta)
    {
        $aFlagsMeta = array();
        if (!empty($aMeta['paging']['num-records']))
        {
            $aFlagsMeta[] = $aMeta['paging']['num-records'];
        }

        return $aFlagsMeta;
    }

    protected function ConstructActions($aActions)
    {
        foreach ($aActions as $aAction)
        {
            $this->sResult .= "<input class=\"button " . (!empty($aAction['class']) ? $aAction['class'] : '') . "\" type=\"submit\" name=\"input[action]\" value=\"" . $aAction['name'] . "\" />\n";
        }
    }

    protected function BuildColumns($aColumns)
    {
        $this->sResult .= "<thead class=\"grid-columns\">\n<tr>\n";
        foreach ($aColumns as $aColumn)
        {
            $aClasses = array('grid-column');
            if (!empty($aColumn['sort']) && !$this->sGroup)
            {
                if (in_array($aColumn['sort'], array(self::SORT_ALPHA, self::SORT_NUMERIC)))
                {
                    $aClasses[] = 'grid-sort-' . $aColumn['sort'];
                }
                else
                {
                    $aClasses[] = 'grid-sort-alpha';
                }
            }
            $sLabel = !empty($aColumn['label'])
            			? $aColumn['label']
            			: '';
            $this->sResult .= '<td class="'. implode(' ', $aClasses) .'">' . $sLabel . '</td>' ."\n";
        }
        $this->sResult .= "</tr>\n</thead>\n";

        $this->BuildData($aColumns, $this->aData);
    }

    protected function BuildData($aHeaders, $aData)
    {
        $this->sResult .= "<tbody class=\"grid-dataset\">\n";
        if (!empty($aData))
        {
            $sGroup = '';
            //$sRowClass = 'row-odd';
            $sRowClass = '';
            $iRowCount = 0;

            foreach ($aData as $sKey => $aRow)
            {
                $iRowCount++;
                // Entry grouping
                if (isset($aRow[$this->sGroup]) && $sGroup != $aRow[$this->sGroup])
                {
                    $this->sResult .= '<tr class="grid-group-header"><td colspan="' . $this->iColSpan . '">' . $aRow[$this->sGroup] . '</td></tr>' ."\n";
                    $this->sResult .= '<tbody class="grid-group" id="grid-group-' . $aRow[$this->sGroup] . '">' ."\n";
                }

                //$sColumnClass = 'column-odd';
                $sColumnClass = '';
                $this->sResult .= "<tr class=\"grid-row\" id=\"grid-row-". $iRowCount . "\">\n";
                foreach ($aHeaders as $aHeader)
                {
                    $sFormat = !empty($aHeader['format']) ? $aHeader['format'] : '';
                    $mValue = '';
                    if (!empty($aHeader['name']))
                    {
                        $mValue = $this->ConstructField($aRow, $aHeader['name'], $sFormat);
                    }

                    #-> If no value is found, assume the default
                    if (!$mValue && !empty($aHeader['default']))
                    {
                        $mValue = $aHeader['default'];
                    }

                    if (!empty($aHeader['type']))
                    {
                        switch ($aHeader['type'])
                        {
                            case 'checkbox':
                                $bIsChecked = !empty($aHeader['data']) && !empty($aRow[$aHeader['data']]);
                                $mValue = '<input type="checkbox" class="grid-checkbox" id="checkbox-'. $aHeader['name'] .'-'. $iRowCount .'" name="input['. $aHeader['name'] .'][]" value="' . $mValue. '" ' . ($bIsChecked ? 'checked="true"' : '') . '/>';
                                break;
                                
                            case 'radio':
                                $bIsChecked = !empty($aHeader['data']) && !empty($aRow[$aHeader['data']]);
                                $mValue = '<input type="radio" class="grid-radio" id="radio-'. $aHeader['name'] .'-'. $iRowCount .'" name="input['. $aHeader['name'] .'][]" value="' . $mValue. '" ' . ($bIsChecked ? 'checked="true"' : '') . '/>';
                                break;
                                
                            case 'list':
                                if (!empty($aHeader['list']) && is_array($aHeader['list']))
                                {
                                    foreach ($aHeader['list'] as $aListItem)
                                    {
                                        if ($aListItem['value'] == $mValue)
                                        {
                                            $mValue = $aListItem['name'];
                                        }
                                    }
                                }
                                break;
                        }
                    }

                    if (!empty($aHeader['link']))
                    {
                        $aParams = array();
                        $sLink = $aHeader['link']['path'];

                        if (count($aHeader['link']['param']))
                        {
                            foreach ($aHeader['link']['param'] as $aParam)
                            {
                                if (!empty($aParam['key']) && !empty($aRow[$aParam['key']]))
                                {
                                    $aParams[] = $aParam['name'] . '=' . $aRow[$aParam['key']];
                                }
                                else if (!empty($aParam['value']))
                                {
                                    $aParams[] = $aParam['name'] . '=' . $aParam['value'];
                                }
                            }
                            $sLink .= (strpos($sLink, '?') === false ? '?' : '&') . implode('&', $aParams);
                        }

                        $mValue = '<a href="' . $sLink . '"' . (!empty($aHeader['link']['tooltip']) ? 'title="' . $aHeader['link']['tooltip'] . '"' : '') . '>' . $mValue . '</a>';
                    }

                    $this->sResult .= '<td class="grid-cell">'. $mValue .'</td>' ."\n";

                    //$sColumnClass = $sColumnClass == 'column-odd' ? 'column-even' : 'column-odd';
                }
                $this->sResult .= "</tr>\n";

                //$sRowClass = $sRowClass == 'row-odd' ? 'row-even' : 'row-odd';

                // Entry grouping
                if (isset($aRow[$this->sGroup]) && $sGroup != $aRow[$this->sGroup])
                {
                    $this->sResult .= '</tbody>';
                    $sGroup = $aRow[$this->sGroup];
                }
            }
        }
        else
        {
            $this->sResult .= "<tr><td colspan=\"". $this->iColSpan ."\"><i>No entries found</i></td></tr>\n";
        }
        $this->sResult .= "</tbody>\n";
        $this->sResult .= "<tfoot>\n";
        $this->sResult .= "<tr><td colspan=\"". $this->iColSpan ."\">\n";

        if (!empty($this->aMeta['action']['footer']))
        {
            $this->ConstructActions($this->aMeta['action']['footer']);
        }

        $this->sResult .= "</td></tr>\n";
        $this->sResult .= "</tfoot>\n";
    }

    protected function BuildIdentity($sIdentity)
    {
        $this->sResult .= "<input type=\"hidden\" name=\"input[identity]\" value=\"" . $sIdentity . "\">\n";
    }

    protected function BuildWorkflow($sWorkflow)
    {
        $this->sResult .= "<input type=\"hidden\" name=\"input[workflow]\" value=\"" . $sWorkflow . "\">\n";
    }

    public function Render($aMeta, $aData = array())
    {
		$aTimer['Start'] = microtime(true);
		
        $this->SetConfig($aMeta, $aData);
        $this->ConstructGridData($aMeta);
        $aFlags = $this->ConstructFlags($aMeta);
        $aRel = $this->ConstructFlagMeta($aMeta);

//        $this->sResult = "<script src=\"html/javascript/lib/grid.js\" type=\"text/javascript\"></script>\n";
        if (!empty($aMeta['identity']) && !empty($aMeta['workflow']))
        {
            $sAction = !empty($aMeta['script']) ? $aMeta['script'] : 'form.php';
            $this->sResult .= "<form method=\"post\" action=\"" . BuildUrl($sAction) ."\" id=\"" . $aMeta['identity'] . "\">\n";
            $this->BuildIdentity($aMeta['identity']);
            $this->BuildWorkflow($aMeta['workflow']);
        }
        $this->sResult .= "<table class=\"" . implode(' ', $aFlags) . "\" rel=\"" . implode(' ', $aRel) . "\" id=\"" . $aMeta['identity'] . "-table\" cellspacing=\"0\">\n<thead>\n";
        
        // Build Headers
        !empty($aMeta['heading'])    && $this->sResult .= '<tr><td colspan="' . $this->iColSpan . '" class="grid-header">'    . $aMeta['heading']    . '</td></tr>' . "\n";
        !empty($aMeta['subheading']) && $this->sResult .= '<tr><td colspan="' . $this->iColSpan . '" class="grid-subheader">' . $aMeta['subheading'] . '</td></tr>' . "\n";
        !empty($aMeta['copy']) 		 && $this->sResult .= '<tr><td colspan="' . $this->iColSpan . '" class="grid-copy"><div>' . $aMeta['copy']       . '</div></td></tr>' . "\n";
        
        // Build quick actions (if required, i.e.: more than 10 entries)
        if (in_array('checkbox', $aFlags) && count($this->aData) > 10)
        {
            $this->sResult .= '<tr><td colspan="' . $this->iColSpan . '" class="grid-quick-select">'
                            . '<span class="clickable checkbox-list-select-all">All</span> | <span class="clickable checkbox-list-select-none">None</span> | <span class="clickable checkbox-list-select-invert">Invert</span>'
                            . "</td></tr>\n";
        }
        
        $this->sResult .= "</thead>\n";
        $this->Build($aMeta);
        $this->sResult .= "</table>\n";

        if (!empty($aMeta['identity']) && !empty($aMeta['workflow']))
        {
            $this->sResult .= "</form>\n";
        }

		$aTimer['End'] = microtime(true);
//		$this->sResult .= '<div class="timer">Time taken to generate: ' . ($aTimer['End'] - $aTimer['Start']) . 's </div>';

        return $this->sResult;
    }
}