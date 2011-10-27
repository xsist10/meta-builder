<?php

/*
 * Builder_Form
 *
 * Copyright(c) 2010, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * Constructs forms from meta data
 */

class Builder_Form extends Builder_Base
{
	/**
     * Parse through the meta and determine if there are any 'file' type inputs
     * in this form. If there are return the appropriate encoding attribute.
     *
     * @param       array       $aMeta
     * @return      string
     */
    private function ___GetFormEncoding($aMeta)
    {
        if (!empty($aMeta['groups']))
        {
            foreach ($aMeta['groups'] as $aGroup)
            {
                if (!empty($aGroup['rows']))
                {
                    foreach ($aGroup['rows'] as $aRow)
                    {
                        if (!empty($aRow['element']))
                        {
                            foreach ($aRow['element'] as $aElement)
                            {
                                if ($aElement['type'] == 'file')
                                {
                                    return 'enctype="multipart/form-data"';
                                }
                            }
                        }
                    }
                }
            }
        }
        return '';
    }

    protected function BuildGroups($aMeta)
    {
        foreach ($aMeta as $aGroup)
        {
            $this->sResult .= "<thead class=\"form-head\">\n";
            !empty($aGroup['heading']) && $this->BuildHeading($aGroup['heading']);
            !empty($aGroup['subheading']) && $this->BuildSubheading($aGroup['subheading']);
            $this->sResult .= "</thead>\n";

            $this->sResult .= "<tbody class=\"form-body\">\n";
            !empty($aGroup['copy']) && $this->BuildCopy($aGroup['copy']);
            if (!empty($aGroup['rows']))
            {
                foreach ($aGroup['rows'] as $aRow)
                {
                    $this->BuildRow($aRow);
                }
            }
            $this->sResult .= "</tbody>\n";
        }
    }

    protected function BuildRow($aRow)
    {
        if (!empty($aRow['copy']))
        {
            $this->BuildInlineCopy($aRow['copy']);
        }

        // Check if all the fields are required
        $bRequired = true;
        if (!empty($aRow['element']))
        {
            foreach ($aRow['element'] as $aElement)
            {
                $bRequired &= !empty($aElement['validation']['required']);
            }
        }
        else
        {
            $bRequired = false;
        }

        $sLabel  = !empty($aRow['label']) ? $aRow['label'] : '&nbsp;';
        $sLabel .= $bRequired ? ' <span class="required">*</span>' : '';
        if (!empty($aRow['image']))
        {
            $sLabel = '<img align="left" src="' . BuildImage($aRow['image']) .'" /> '. $sLabel;
        }

        $this->sResult .= "<tr>\n";
        $this->sResult .= "<td class=\"form-label\"" . (!empty($aRow['id']) ? " id=\"" . $aRow['id'] ."-label\"" : "") . ">" . $sLabel . "</td>\n";
        $this->sResult .= "<td class=\"form-value\"" . (!empty($aRow['id']) ? " id=\"" . $aRow['id'] ."-value\"" : "") . ">";
        $oElement = new Builder_Form_Element();

        if (!empty($aRow['element']))
        {
            foreach ($aRow['element'] as $aElement)
            {
                $this->sResult .= $oElement->Build($aElement, $this->aData);
            }
        }
        $this->sResult .= "</td>\n";
        $this->sResult .= "</tr>\n";
    }

    protected function BuildHeading($sHeading)
    {
        $this->sResult .= '<tr><td colspan="2" class="form-header">' . $sHeading . '</td></tr>' . "\n";
    }

    protected function BuildSubheading($sSubheading)
    {
        $this->sResult .= '<tr><td colspan="2" class="form-subheader">' . $sSubheading . '</td></tr>' . "\n";
    }

    protected function BuildInlineCopy($sCopy)
    {
        $this->sResult .= '<tr><td colspan="2" class="form-inline-copy">' . $sCopy . '</td></tr>' . "\n";
    }

    protected function BuildCopy($sCopy)
    {
        $this->sResult .= '<tr><td colspan="2" class="form-copy">' . $sCopy . '</td></tr>' . "\n";
    }

    protected function BuildIdentity($sIdentity)
    {
        $this->sResult .= "<input type=\"hidden\" name=\"input[identity]\" value=\"" . $sIdentity . "\">\n";
    }

    protected function BuildWorkflow($sWorkflow)
    {
        $this->sResult .= "<input type=\"hidden\" name=\"input[workflow]\" value=\"" . $sWorkflow . "\">\n";
    }
    
    protected function BuildAction($sAction)
    {
        $this->sResult .= "<input type=\"hidden\" name=\"input[action]\" value=\"" . $sAction . "\">\n";
    }

    public function Render($aMeta, $aData = array())
    {
		$aTimer['Start'] = microtime(true);
		
		$this->sResult = '';
		
        if (empty($aMeta['identity']))
        {
            throw new Exception('No identity specified');
        }

        //empty($aData) && $aData = Session::GetInput($aMeta['identity']);
        $this->SetConfig($aMeta, $aData);

        $sAction = !empty($aMeta['script']) ? $aMeta['script'] : 'form.php';

        $this->sResult .= "<form method=\"post\" ". $this->___GetFormEncoding($aMeta) ." action=\"" . BuildUrl($sAction) ."\" id=\"" . $aMeta['identity'] . "\">\n";
        $this->sResult .= "<table class=\"form\" id=\"" . $aMeta['identity'] . "-table\">\n";
        $this->Build($aMeta);
        $this->sResult .= "</table>\n";

        // Enable Validation for this Form
        $this->sResult .= "
        <script>
        $(document).ready(function()
        {
			$('#" . $aMeta['identity'] . "').validator({
				inputEvent: 	blur,
		    	messageClass:   'validation-error'
    		});
    	});
        </script>
        ";

        $this->sResult .= "</form>\n";

		$aTimer['End'] = microtime(true);
//		$this->sResult .= '<div class="timer">Time taken to generate: ' . ($aTimer['End'] - $aTimer['Start']) . 's </div>';

        return $this->sResult;
    }
}