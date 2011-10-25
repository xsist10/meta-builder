<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Meta Builder Example</title>
	<meta http-equiv="Content-Language" content="English" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<link rel="stylesheet" type="text/css" href="../html/css/Builder.css" media="screen" />

	<!-- Include Core Javascript Files -->
	<script src="../html/javascript/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="../html/javascript/jquery.tools.min.js" type="text/javascript"></script>
	<script src="../html/javascript/form.js" type="text/javascript"></script>
	<script src="../html/javascript/grid.js" type="text/javascript"></script>
	<script src="../html/javascript/tiny_mce/tiny_mce_dev.js" type="text/javascript"></script>
</head>

<body>
<?php

require_once '../lib/Builder.php';

try
{
	$aListItems = array(
		array('value' => 'Mr'),
		array('value' => 'Mrs'),
		array('value' => 'Ms'),
		array('value' => 'Mz'),
		array('value' => 'Dr', 'name' => 'Doctor'),
		array('value' => 'Prof', 'name' => 'Professor'),
	);
	$oList = new Builder_Helper_List();
	$oList->ImportListItems($aListItems);
	
	$oElement1 = new Builder_Helper_Element('salutation', 'list');
	$oElement1->SetRenderMode('select')->SetList($oList)
			  ->SetDescription('Please your salutation')
			  ->SetValidation(new Builder_Helper_Validation(array('required' => true)));
	
	$oRow1 = new Builder_Helper_Row('Title');
	$oRow1->AddElement($oElement1);
	
	$oElement2 = new Builder_Helper_Element('first_name');
	$oElement2->SetHint('Put your first name here')
			  ->SetDescription('Please enter a name between 4 and 25 characters long')
			  ->SetValidation(new Builder_Helper_Validation(array('required' => true, 'length' => array('min' => 4, 'max' => 25))));
	
	$oRow2 = new Builder_Helper_Row('First Name');
	$oRow2->AddElement($oElement2);
	
	$oElement3 = new Builder_Helper_Element('last_name');
	$oElement3->SetTooltip('Please enter a name between 4 and 25 characters long')
			  ->SetValidation(new Builder_Helper_Validation(array('required' => true, 'length' => array('min' => 4, 'max' => 25))));
	
	$oRow3 = new Builder_Helper_Row('Last Name');
	$oRow3->AddElement($oElement3);
	
	// Description
	$oElement4 = new Builder_Helper_Element('description', 'textarea');
	$oElement4->SetValidation(new Builder_Helper_Validation(array('length' => array('max' => 255))))
			  ->SetTooltip('Describe yourself in under 255 characters')
			  ->SetRows(8)->SetCols(45);
	
	$oRow4 = new Builder_Helper_Row('Description');
	$oRow4->AddElement($oElement4);
	
	// Address
	$oElement5 = new Builder_Helper_Element('address');
	$oElement5->SetHint('Physical Address')
			  ->SetValidation(new Builder_Helper_Validation(array('required' => true, 'length' => array('min' => 5, 'max' => 255))))
			  ->SetSize(30);
	
	// Postal Code
	$oElement6 = new Builder_Helper_Element('postal_code');
	$oElement6->SetHint('Postal Code')
			  ->SetTooltip('Format: XXXX-XX-XXX')
			  ->SetValidation(new Builder_Helper_Validation(array('required' => true, 'length' => array('min' => 11, 'max' => 11))));
	
	$oRow5 = new Builder_Helper_Row('Address');
	$oRow5->AddElement($oElement5)->AddElement($oElement6);
	
	// Date of birth
	$oElement9 = new Builder_Helper_Element('dob', 'date');
	//$oElement9;
	$oRow7 = new Builder_Helper_Row('Date of Birth');
	$oRow7->AddElement($oElement9);
	
	// Language Dropdown
	$aListItems = array(
		array('value' => 'af',    'name' => 'Afrikaans'),
		array('value' => 'ar-sa', 'name' => 'Arabic (Saudi Arabia)'),
		array('value' => 'nl-be', 'name' => 'Dutch (Belgium)'),
		array('value' => 'en-US', 'name' => 'English (US)'),
		array('value' => 'en-UK', 'name' => 'English (UK)'),
		array('value' => 'fr',    'name' => 'French (Standard)'),
	);
	
	$oList4 = new Builder_Helper_List();
	$oList4->ImportListItems($aListItems);
	$oElement10 = new Builder_Helper_Element('language', 'list');
	$oElement10->SetRenderMode('select')->SetList($oList4);
	$oRow8 = new Builder_Helper_Row('Language');
	$oRow8->AddElement($oElement10);
	 
	$oList = new Builder_Helper_List();
	$aListItems = array();
	$oList->ImportListItems($aListItems);
	
	// Build a submit button
	$oElement11 = new Builder_Helper_Element('Action', 'submit');
	$oElement11->SetValue('Add Download');
	
	// Build a row for our submit button
	$oRow9 = new Builder_Helper_Row();
	$oRow9->AddElement($oElement11);
	
	// Combine all our rows
	$oRowsHelper = new Builder_Helper_Rows();
	$oRowsHelper->AddRow($oRow1)->AddRow($oRow2)
				->AddRow($oRow3)->AddRow($oRow4)
				->AddRow($oRow5)->AddRow($oRow7)
				->AddRow($oRow8)->AddRow($oRow9);
	
	// Add our rows to a group with it's own heading and sub heading
	$oGroupHelper = new Builder_Helper_Group();
	$oGroupHelper->SetHeading('Subscribe')->SetSubheading('Please provide these basic details to register.');
	$oGroupHelper->SetRows($oRowsHelper);
	
	// Combine our groups
	$oGroupsHelper = new Builder_Helper_Groups();
	$oGroupsHelper->AddGroup($oGroupHelper);
	
	// Add our groups to our form helper and set some basic attributes
	$oFormHelper = new Builder_Helper_Form('test-form');
	$oFormHelper->AddGroups($oGroupsHelper)
				->SetWorkflow('register')
				->SetAction('register');
	
	// Build our form
	$oForm = new Builder_Form();
	echo $oForm->Render($oFormHelper->ToArray());
}
catch (Exception $oException)
{
	echo 'Exception: ' . $oException->getMessage();
}
?>
</body>
</html>