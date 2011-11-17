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
	
	
	<!-- standalone page styling (can be removed) -->
 	<script src="../html/javascript/tiny_mce/tiny_mce_dev.js" type="text/javascript"></script>
</head>

<body>
<?php

require_once '../lib/Builder.php';

try
{
	$oElement = new Builder_Helper_Element('Username');
	$oElement->SetHint('Select a unique username')
			 ->SetDescription('Please enter a user name between 4 and 25 characters long')
			 ->SetValidation(new Builder_Helper_Validation(array('required' => true, 'length' => array('min' => 4, 'max' => 25))))
			 // Repopulate the value in the field
			 ->SetValue('Bob')
			 // Add the error message
			 ->SetError('Username is already in use.');
	
	$oRow = new Builder_Helper_Row('First Name');
	$oRow->AddElement($oElement);
	
	// Combine all our rows
	$oRowsHelper = new Builder_Helper_Rows();
	$oRowsHelper->AddRow($oRow);
	
	$oGroupHelper = new Builder_Helper_Group();
	$oGroupHelper->SetHeading('Error on load.')
				 ->SetSubheading('This will show an error when the form loads. Useful for returning the user to a form to correct the data being captured.')
				 ->SetRows($oRowsHelper);
	
	// Combine our groups
	$oGroupsHelper = new Builder_Helper_Groups();
	$oGroupsHelper->AddGroup($oGroupHelper);
	
	// Add our groups to our form helper and set some basic attributes
	$oFormHelper = new Builder_Helper_Form('test-form');
	$oFormHelper->AddGroups($oGroupsHelper)->SetScript('post_to_this_form.php');
	
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