<?php

/*
 * Builder_Form_Element
 *
 * Copyright(c) 2010, Thomas Shone
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 * http://www.shone.co.za
 *
 * Constructs forms elements from meta data
 */

class Builder_Form_Element
{
    private $aMeta;
    private $aData;
    private $aClasses;
    private $aExtras;
    private $aAttributes;

    // -------------------------------------------------------------------------
    // Constants

    // Basic Element Types
    const TYPE_TEXT                 = 'text';
    const TYPE_RICH_TEXT			= 'rich_text';
    const TYPE_CHECKBOX             = 'checkbox';
    const TYPE_RADIO                = 'radio';
    const TYPE_HIDDEN               = 'hidden';
    const TYPE_BUTTON               = 'button';
    const TYPE_SUBMIT               = 'submit';
    const TYPE_FILE                 = 'file';
    const TYPE_EMAIL                = 'email';
    const TYPE_NUMBER               = 'number';
    const TYPE_TELEPHONE			= 'telephone';
    const TYPE_URL                  = 'url';
    const TYPE_DATE                 = 'date';
    const TYPE_TIME                 = 'time';
    const TYPE_DATETIME             = 'datetime';
    const TYPE_PASSWORD             = 'password';
    const TYPE_CAPTCHA				= 'captcha';
    const TYPE_LIST					= 'list';
    const TYPE_HTML					= 'html';

    // Element Groups
    const GROUP_TYPE_UNKNOWN        = 0;
    const GROUP_TYPE_DATETIME       = 5;
    
    // Render Mode
    const RENDER_MODE_CHECKBOX		= 'checkbox';
    const RENDER_MODE_RADIO			= 'radio';
    const RENDER_MODE_SELECT		= 'select';

    // Predefined Validation Regex Matches
    const VALIDATION_ALPHA          = '[a-zA-Z ]';
    const VALIDATION_NUMERIC        = '[0-9]';
    const VALIDATION_ALPHANUMERIC   = '[a-zA-Z0-9 ]';
    const VALIDATION_DATE           = '(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])';
    const VALIDATION_TIME           = '([01][1-9]|2[01234]):([0-5][1-9]|60):([0-5][1-9]|60)';
    const VALIDATION_DATETIME       = '(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) ([01][1-9]|2[01234]):([0-5][1-9]|60):([0-5][1-9]|60)';

    // Element Groupings
    private static $aTypeGroups = array(
        self::GROUP_TYPE_DATETIME => array(
            self::TYPE_DATE,
            self::TYPE_TIME,
            self::TYPE_DATETIME
        )
    );

    // -------------------------------------------------------------------------
    // Private Functions
    private function ___GetGroup($sType)
    {
        foreach (self::$aTypeGroups as $iGroupType => $aGroups)
        {
            if (in_array($sType, $aGroups))
            {
                return $iGroupType;
            }
        }

        return self::GROUP_TYPE_UNKNOWN;
    }

    private function ___GetData()
    {
        // Don't attempt to set data for the following elements
        $aExcludeList = array('submit');
        if (in_array($this->aMeta['type'], $aExcludeList))
        {
            return $this->aMeta['value'];
        }

        $mValue = '';
        // Look up post specific input match
        /*$mValue = Input::GetPost($this->aMeta['name']);
        if ($mValue)
        {
            return $mValue;
        }

        // If nothing there, look up non-post specific input match
        $mValue = Input::Get($this->aMeta['name']);
        if ($mValue)
        {
            return $mValue;
        }

        // Finally look up possible session store
        $mValue = Session::GetKey('Input', $this->aMeta['name']);
        if ($mValue)
        {
            return $mValue;
        }*/

        // Look up data passed
        if (!empty($this->aData[$this->aMeta['name']]))
        {
            return $this->aData[$this->aMeta['name']];
        }


        if (!empty($this->aMeta['value']))
        {
            return $this->aMeta['value'];
        }
        return '';
    }

    private function ___CheckMeta()
    {
        if (empty($this->aMeta['name']))
        {
            throw new Exception('Name Required.');
        }
        if (empty($this->aMeta['type']))
        {
            throw new Exception('Type Required.');
        }

        $this->aMeta['value'] = $this->___GetData();
    }

    private function ___ConstructAttributes()
    {
        if (empty($this->aMeta['id']))
        {
            $this->aMeta['id'] = $this->aMeta['name'];
        }

        $this->aMeta['render-mode'] = !empty($this->aMeta['render-mode'])
                                        ? $this->aMeta['render-mode']
                                        : '';

        $aFields = array('type', 'name', 'id', 'value', 'cols', 'rows',
                        'class', 'size', 'maxlength', 'hint', 'tooltip',
                        'autocomplete');

        foreach ($aFields as $sField)
        {
            if (!empty($this->aMeta[$sField]))
            {
                switch ($sField)
                {
                    case 'name':
                        //$this->aAttributes[$sField] = 'input['. $this->aMeta[$sField] .']';
                    	$this->aAttributes[$sField] = $this->aMeta[$sField];
                        break;

                    case 'hint':
                        $this->aAttributes[$sField] = $this->aMeta[$sField];
                        $this->aClasses[] = 'input-hint-present';
                        break;

                    case 'tooltip':
                        $this->aAttributes['title'] = $this->aMeta[$sField];
                        break;
                        
                    default:
                        if (is_array($this->aMeta[$sField]))
                        {
                            $this->aAttributes[$sField] = array_map('strip_tags', $this->aMeta[$sField]);
                        }
                        else
                        {
                            $this->aAttributes[$sField] = str_replace(array('"', '%22'), "'", strip_tags($this->aMeta[$sField]));
                        }
                        break;
                }
            }
        }
        
        #-> Never, ever repopulate password fields. For any reason!
        if (!empty($this->aAttributes['type'])
            && $this->aAttributes['type'] == self::TYPE_PASSWORD)
        {
            $this->aAttributes['value'] = '';
        }
    }

    private function ___ConstructExtras()
    {
        if (!empty($this->aMeta['image']))
        {
            $this->aExtras[] = '<img id="'. $this->aMeta['name'] .'-image" src="'. BuildImage($this->aMeta['image']) .'" />';
        }

        if (!empty($this->aMeta['description']))
        {
            $this->aExtras[] = '<span id="'. $this->aMeta['name'] .'-description" class="description">'. $this->aMeta['description'] .'</span>';
        }
        
        if (!empty($this->aMeta['error']))
        {
        	$this->aExtras[] = '<span class="messages-error messages-error-inline">'. $this->aMeta['error'] .'</span>';
        	$this->aClasses[] = 'input-error';
        }

        /*$aInlineErrors = Messages::GetInlineErrors($this->aMeta['name']);
        if ($aInlineErrors && count($aInlineErrors))
        {
            $this->aExtras[] = '<span class="message-error message-error-inline">'. implode('<br/>', $aInlineErrors) .'</span>';
            $this->aClasses[] = 'input-error';
        }*/
    }

    /**
     * Determine which classes need to be applied to the DOM
     */
    private function ___ConstructClasses()
    {
        if ($this->aMeta['type'] == self::TYPE_SUBMIT)
        {
            $this->aClasses[] = 'input-submit';
        }
        if (!empty($this->aMeta['render']['class']))
        {
            $this->aClasses[] = $this->aMeta['render']['class'];
        }
    }

    /**
     * Perform all the meta data alterations (DOM attributes, CSS classes, etc)
     * required to properly perform the validation checks
     */
    private function ___ConstructValidation()
    {
        // Build Validation
        if (!empty($this->aMeta['validation']))
        {
            // For date/time fields, validation is handled differently
            $iGroupType = $this->___GetGroup($this->aMeta['type']);
            
            /*// Build attributions
            if (!empty($this->aMeta['validation']['length']['max']))
            {
                if ($this->aMeta['type'] == self::TYPE_NUMBER
                    || $iGroupType == self::GROUP_TYPE_DATETIME)
                {
                   $this->aAttributes['max'] = $this->aMeta['validation']['length']['max'];
                }
                else
                {
                   $this->aAttributes['maxlength'] = $this->aMeta['validation']['length']['max'];
                   $this->aMeta['validation']['length']['max'] < 20
                       && $this->aAttributes['size'] = $this->aMeta['validation']['length']['max'];
                }
            }
            if (!empty($this->aMeta['validation']['length']['min']))
            {
                if ($this->aMeta['type'] == self::TYPE_NUMBER
                    || $iGroupType == self::GROUP_TYPE_DATETIME)
                {
                   $this->aAttributes['min'] = $this->aMeta['validation']['length']['min'];
                }
                else
                {
                    $this->aAttributes['minlength'] = $this->aMeta['validation']['length']['min'];
                }
            }*/
            
            // Certain elements don't need custom validation
            // This will be removed later
            $bSkipMatchValidation = false;
            switch ($this->aMeta['type'])
            {
//                case self::TYPE_EMAIL:
                case self::TYPE_NUMBER:
                case self::TYPE_URL:
                case self::TYPE_DATE:
                case self::TYPE_TIME:
                case self::TYPE_DATETIME:
                    $bSkipMatchValidation = true;
                    break;
                default:
                    break;
            }

            if (!$bSkipMatchValidation)
            {
                // Build fields on Min and Max values
                if (!empty($this->aMeta['validation']['length']['min']))
                {
                    // For numbers, we don't want to use a min length validation check
                    if ($this->aMeta['type'] == self::TYPE_NUMBER
                    	|| $iGroupType == self::GROUP_TYPE_DATETIME)
                    {
                        $this->aAttributes['min'] = $this->aMeta['validation']['length']['min'];
                    }
                    else
                    {
                        $this->aAttributes['minlength'] = $this->aMeta['validation']['length']['min'];
                    }
                }
                if (!empty($this->aMeta['validation']['length']['max']))
                {
                    // For numbers, we don't want to use max length validation check
                    if ($this->aMeta['type'] == self::TYPE_NUMBER
                    	|| $iGroupType == self::GROUP_TYPE_DATETIME)
                    {
                        $this->aAttributes['max'] = $this->aMeta['validation']['length']['max'];
                    }
                    else
                    {
                        $this->aAttributes['maxlength'] = $this->aMeta['validation']['length']['max'];
                        $this->aMeta['validation']['length']['max'] < 20
                            && $this->aAttributes['size'] = $this->aMeta['validation']['length']['max'];
                    }
                }
                
                !empty($this->aMeta['validation']['num_checked'])
                	&& $this->aAttributes['num_checked'] = $this->aMeta['validation']['num_checked'];
                
                !empty($this->aMeta['validation']['required'])
                	&& $this->aAttributes['required'] = 'required';

                if (!empty($this->aMeta['validation']['pattern']))
                {
                    switch ($this->aMeta['validation']['pattern'])
                    {
                        case '.':
                            break;

                        case 'ajax':
                            $this->aAttributes['custom'] = $this->aMeta['validation']['script'];
                            break;

                        default:
                            $this->aAttributes['pattern'] = $this->aMeta['validation']['pattern'];
                            break;
                    }
                }
                
                if (!empty($this->aMeta['validation']['match']))
                {
                    $this->aAttributes['data-equals'] = 'input[' . $this->aMeta['validation']['match'] . ']';
                }
            }
        }
    }

    private function RenderAttributes($aAttributes = array())
    {
    	$aAttributes = !empty($aAttributes)
    					? $aAttributes
    					: $this->aAttributes;
        $sResult = '';
        foreach ($aAttributes as $sKey => $sValue)
        {
             $sResult .= ' ' . $sKey . '="' . $sValue . '"';
        }
        return $sResult;
    }

    private function RenderExtras()
    {
        return implode(' ', $this->aExtras);
    }

    private function RenderClasses()
    {
        return implode(' ', $this->aClasses);
    }

    private function RenderLabel()
    {
        return $this->aMeta['label'];
    }

    private function RenderBasic()
    {
        return "\n\t" . '<input' . $this->RenderAttributes() . ' class="' . $this->RenderClasses() . '" /> ' . $this->RenderExtras();
    }

    private function RenderText()
    {
        return $this->RenderBasic();
    }

    private function RenderNumber()
    {
        return $this->RenderBasic();
    }

    private function RenderUrl()
    {
        return $this->RenderBasic();
    }

    private function RenderEmail()
    {
        return $this->RenderBasic();
    }

    private function RenderTelephone()
    {
        return $this->RenderBasic();
    }

    private function RenderTextarea()
    {
        $sValue = '';
        if (!empty($this->aAttributes['value']))
        {
            $sValue = $this->aAttributes['value'];
            unset($this->aAttributes['value']);
        }
        return "\n\t" . '<textarea' . $this->RenderAttributes() . ' class="' . $this->RenderClasses() . '" />' . $sValue . '</textarea> ' . $this->RenderExtras();
    }

    private function RenderPassword()
    {
        return $this->RenderBasic();
    }

    private function RenderSubmit()
    {
        return $this->RenderBasic();
    }

    private function RenderFile()
    {
        return $this->RenderBasic();
    }

    private function RenderCheckbox()
    {
        $this->aAttributes['value'] = 1;
        return $this->RenderBasic();
    }

    private function RenderHidden()
    {
        return $this->RenderBasic();
    }

    private function RenderRadioList()
    {
        $sRenderMode = $this->aMeta['render-mode'];
        $sId = $this->aAttributes['id'];
        $sValidation = !empty($this->aMeta['validation']['num_checked'])
        				? 'num_checked="' . $this->aMeta['validation']['num_checked'] . '"'
        				: '';
        
        $sResult = '<ul class="' . $sRenderMode . '-list" id="' . $sRenderMode . '-list-' . $sId . '" '. $sValidation . '>';
        
        if ($sRenderMode == self::TYPE_CHECKBOX)
        {
            $sName = $this->aAttributes['name'];
            $this->aAttributes['name'] .= '[]';

            if (count($this->aMeta['list']) > 5)
            {
                $sResult .= '<li><span class="clickable checkbox-list-select-all">All</span> | <span class="clickable checkbox-list-select-none">None</span> | <span class="clickable checkbox-list-select-invert">Invert</span></li>';
            }
        }
                
        foreach ($this->aMeta['list'] as $aEntry)
        {
            $sName = !empty($aEntry['name'])
                        ? $aEntry['name']
                        : $aEntry['value'];

            unset($this->aAttributes['checked']);
            $this->aAttributes['value'] = $aEntry['value'];
            $this->aAttributes['id'] = $sId .'-'. str_replace(' ', '', htmlentities($sName));

            $sLabel = $sName;
            if (!empty($aEntry['image']))
            {
                $sLabel = '<img src="'. BuildImage($aEntry['image']) .'" /> '. $sLabel;
            }
            
            if (!empty($this->aMeta['value']))
            {
                // Check if we have an array or a single value
                if (is_array($this->aMeta['value']) && in_array($aEntry['value'], $this->aMeta['value']))
                {
                    $this->aAttributes['checked'] = 'true';
                }
                else if ($aEntry['value'] == $this->aMeta['value'])
                {
                    $this->aAttributes['checked'] = 'true';
                }
            }

            $sResult .= '<li><input '. $this->RenderAttributes() .'><label for="'. $this->aAttributes['id'] .'">'. $sLabel .'</label></li>' ."\n";
        }
        $sResult .= '</ul>';
        
        $this->aAttributes['name'] = $sName;

        return $sResult;
    }

    private function RenderCheckList()
    {
        return $this->RenderRadioList();
    }

    private function RenderSelectList()
    {
        $sResult = '<select '. $this->RenderAttributes() .'>' ."\n";
        if (!empty($this->aMeta['list']))
        {
            foreach ($this->aMeta['list'] as $aEntry)
            {
                $sLabel = !empty($aEntry['name'])
                            ? $aEntry['name']
                            : $aEntry['value'];

                $aAttributes = array('value' => $aEntry['value']);
                if (!empty($aEntry['image']))
                {
                	#-> TODO: This only works for Firefox
                	$aAttributes = array('style' => 'background: url(' . BuildImage($aEntry['image']) . ') 1px 3px no-repeat; padding-left: 20px;');
                    //$sLabel = '<img src="'. BuildImage($aEntry['image']) .'" /> '. $sLabel;
                }

                if (!empty($this->aMeta['value']) && $aEntry['value'] == $this->aMeta['value'])
                {
                	$aAttributes = array('selected' => $aEntry['selected']);
                }

                $sResult .= '<option ' . $this->RenderAttributes($aAttributes) . '>'. $sLabel .'</option>' ."\n";
            }
        }
        $sResult .= '</select>' ."\n";

        return $sResult;
    }

    private function RenderList()
    {
        $sResult = '';
        if (!empty($this->aMeta['script']))
        {
            $sResult .= "<script>
$(document).ready(function()
{
    $.getJSON('" . BuildUrl($this->aMeta['script']) . "',
    function(data)
    {
        $.each(data, function(i,item)
        {
            $('#" . $this->aMeta['id'] . "').append('<option value=\"' + item.value + '\" ' + (\"". $this->aMeta['value'] ."\" == item.value ? 'selected=\"true\"' : '') + '>' + item.name + '</option>');
        });
    });
});
        </script>";
        }

        switch ($this->aMeta['render-mode'])
        {
            case 'radio':
                $this->aAttributes['type'] = 'radio';
                $sResult .= $this->RenderRadioList();
                break;
            case 'checkbox':
                $this->aAttributes['type'] = 'checkbox';
                $sResult .= $this->RenderCheckList();
                break;
            case 'select':
            default:
                $sResult .= $this->RenderSelectList();
                break;
        }

        return $sResult . $this->RenderExtras();
    }
    
    private function RenderCaptcha()
    {
    	require_once('3rd/reCAPTCHA/recaptchalib.php');
    	return recaptcha_get_html($this->aAttributes['value']) . $this->RenderExtras();
    }
    
    private function RenderRichtext()
    {
    	$oRichtext = new Builder_Form_Element_Richtext();
    	$sResult = $oRichtext->Build($this->aMeta, $this->aData);
    	
    	$this->aAttributes['cols'] = 60;
    	$this->aAttributes['rows'] = 15;
    		
    	return $sResult . $this->RenderTextarea();
    }

    private function RenderDate()
    {
        ctype_digit($this->aAttributes['value'])
            && $this->aAttributes['value'] = date('Y-m-d', $this->aAttributes['value']);

        switch ($this->aMeta['render-mode'])
        {
            case 'select':
                throw new Exception('Select Box Date Field not currently supported.');

            default:
                $this->aAttributes['size'] = 10;
                $this->aAttributes['maxlength'] = 10;
                return $this->RenderBasic();
        }
    }

    private function RenderTime()
    {
        $iValue = time();
        if ($this->aMeta['value'])
        {
            if (is_array($this->aMeta['value']))
            {
                $aValue = $this->aMeta['value'];
                $iValue = strtotime($aValue['date'] .' '. $aValue['hour'] .':'. $aValue['minute'] .':00');
            }
            else
            {
                $iValue = strtotime($this->aMeta['value']);
            }
        }
        $this->aAttributes['value'] = $iValue;

        switch ($this->aMeta['render-mode'])
        {
            case 'select':
            default:
                return $this->___RenderTimeSelect() . $this->RenderExtras();
        }
    }

    private function RenderDatetime()
    {
        $iValue = time();
        if ($this->aMeta['value'])
        {
            if (is_array($this->aMeta['value']))
            {
                $aValue = $this->aMeta['value'];
                $iValue = strtotime($aValue['date'] .' '. $aValue['hour'] .':'. $aValue['minute'] .':00');
            }
            else if ($this->aMeta['value'])
            {
                $iValue = $this->aAttributes['value'];
            }
            else
            {
                $iValue = strtotime($this->aMeta['value']);
            }
        }
        $this->aAttributes['value'] = $iValue;

        $sTime = '';
        switch ($this->aMeta['render-mode'])
        {
            case 'select':
                throw new Exception('Select Box Date Field not currently supported.');

            default:
                // Build time selector
                $sTime = $this->___RenderTimeSelect();
                // Add to extras array
                array_unshift($this->aExtras, ' @ ' . $sTime);

                // Change Type to Date for the Date Selector
                $this->aAttributes['type'] = self::TYPE_DATE;

                // Because we have a multi-part input element, we need to tag
                // the date field with a date array name
                $this->aAttributes['id'] = $this->aAttributes['id'] . '[date]';
                $this->aAttributes['name'] = $this->aAttributes['name'] . '[date]';

                return $this->RenderDate();
                break;
        }
    }

    private function ___RenderTimeSelect()
    {
        $sId = $this->aAttributes['id'];
        $sName = $this->aAttributes['name'];

        $iValue = $this->aAttributes['value']
                    ? $this->aAttributes['value']
                    : time();
        $aTime = array(
            'hour'   => date('H', $iValue),
            'minute' => date('i', $iValue),
        );

        $this->aAttributes['id'] = $sId . '-hour';
        $this->aAttributes['name'] = $sName . '[hour]';

        $sResult = '<select '. $this->RenderAttributes() .' class="' . $this->RenderClasses() . '">' ."\n";
        for ($i=0; $i<24; $i++)
        {
            $sHour = str_pad($i, 2, '0', STR_PAD_LEFT);
            $sResult .= '<option'. ($aTime['hour'] == $i ? ' selected' : '') . '>'. $sHour .'</option>' ."\n";
        }
        $sResult .= '</select> H '."\n";

        $this->aAttributes['id'] = $sId . '-minute';
        $this->aAttributes['name'] = $sName . '[minute]';

        $sResult .= '<select '. $this->RenderAttributes() .' class="' . $this->RenderClasses() . '">' ."\n";
        for ($i=0; $i<60; $i++)
        {
            $sMinute = str_pad($i, 2, '0', STR_PAD_LEFT);
            $sResult .= '<option'. ($aTime['minute'] == $i ? ' selected' : '') . '>'. $sMinute .'</option>' ."\n";
        }
        $sResult .= '</select> M ' ."\n";

        $this->aAttributes['id'] = $sId;
        $this->aAttributes['name'] = $sName;

        return $sResult;
    }
    
    private function RenderHtml()
    {
    	return $this->aMeta['value'];
    }

    // TODO: Implement Tags Element
/*    private function RenderTags()
    {
        $sResult  = '<input '. $this->RenderAttributes() .'>' . $this->RenderExtras() ."\n";
        $sResult .= "<script>
$(document).ready(function() {
    $('#" . $this->aMeta['id'] . "').tokenInput('" . $this->aMeta['script'] . "', {});
});
        </script>";

        return $sResult;
    }
	*/

    // -------------------------------------------------------------------------
    // Public Functions
    public function Build($aMeta, $aData = array())
    {
        $this->aMeta        = $aMeta;
        $this->aData        = $aData;
        $this->aClasses     = array();
        $this->aExtras      = array();
        $this->aAttributes  = array();

        $aMethods = get_class_methods(__CLASS__);
        $sRenderFunction = 'Render' . ucwords(str_replace('_', '', $this->aMeta['type']));
        if (in_array($sRenderFunction, $aMethods))
        {
            $this->___CheckMeta();
            $this->___ConstructAttributes();
            $this->___ConstructExtras();
            $this->___ConstructClasses();
            $this->___ConstructValidation();

            return $this->$sRenderFunction();
        }
        else
        {
            throw new Exception('Unsupported Type');
        }
    }

}
