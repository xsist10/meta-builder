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
	
	$aCompanies = array(
		array(
			'id'            => 1,
			'name'          => 'Company A',
			'category'      => 'Service',
			'description'   => 'Company A provides a service',
			'coords'		=> '28,-81',
		),
		array(
	        'id'            => 4,
	        'name'          => 'Company D',
	        'category'      => 'Service',
	        'description'   => 'Company D provides a service',
	        'coords'		=> '40,-80',
		),
		array(
			'id'            => 7,
			'name'          => 'Company G',
			'category'      => 'Service',
			'description'   => 'Company G provides a service',
			'coords'		=> '50,15',
		),
		array(
			'id'            => 10,
			'name'          => 'Company J',
	        'category'      => 'Service',
	        'description'   => 'Company J provides a service',
	        'coords'		=> '50,15',
		),
		array(
			'id'            => 2,
			'name'          => 'Company B',
			'category'      => 'Manufactorer',
			'description'   => 'Company B makes plastic cup holders',
			'coords'		=> '50,50',
		),
		array(
	        'id'            => 5,
	        'name'          => 'Company E',
	        'category'      => 'Manufactorer',
			'description'   => 'Company E makes plastic cup holders',
			'coords'		=> '40,30',
		),
		array(
			'id'            => 8,
			'name'          => 'Company H',
			'category'      => 'Manufactorer',
			'description'   => 'Company H makes plastic cup holders',
			'coords'		=> '30,40',
		),
		array(
			'id'            => 11,
			'name'          => 'Company K',
	        'category'      => 'Manufactorer',
	        'description'   => 'Company K makes plastic cup holders',
			'coords'		=> '0,30',
		),
		array(
	        'id'            => 3,
	        'name'          => 'Company C',
	        'category'      => 'Retail',
	        'description'   => 'Company C sells the items that Company B makes',
	        'coords'		=> '20,15',
		),
		array(
			'id'            => 6,
			'name'          => 'Company F',
			'category'      => 'Retail',
			'description'   => 'Company F sells the items that Company E makes',
			'coords'		=> '15,20',
		),
		array(
			'id'            => 9,
			'name'          => 'Company I',
			'category'      => 'Retail',
			'description'   => 'Company I sells the items that Company H makes',
			'coords'		=> '51.513016,-0.056305',
		),
		array(
			'id'            => 12,
			'name'          => 'Company L',
			'category'      => 'Retail',
			'description'   => 'Company L sells the items that Company K makes',
			'coords'		=> '30,0',
		),
	);
	
	$oColumn1 = new Builder_Helper_Column();
	$oColumn1->SetName('id')->SetType('radio');
	
	$oColumn2 = new Builder_Helper_Column();
	$oColumn2->SetName('id')->SetLabel('Id');
	
	$oParam = new Builder_Helper_Link_Param();
	$oParam->SetName('id')->SetKey('id');
	$oLink = new Builder_Helper_Link();
	$oLink->SetPath(BuildUrl('company.php'))->SetParam($oParam);
	
	$oColumn3 = new Builder_Helper_Column();
	$oColumn3->SetName('name')->SetLabel('Name')
			 ->SetLink($oLink);
	
	$oColumn4 = new Builder_Helper_Column();
	$oColumn4->SetName('category')->SetLabel('Category');
	
	$oColumn5 = new Builder_Helper_Column();
	$oColumn5->SetName('coords')->SetLabel('Map')
			 ->SetFormat('coordinates');
	
	$oColumn6 = new Builder_Helper_Column();
	$oColumn6->SetName('description')->SetLabel('Description')
			 ->SetFormat('truncate', array(40, '...'));
	
	$oColumns = new Builder_Helper_Columns();
	$oColumns->AddColumn($oColumn1)->AddColumn($oColumn2)
			 ->AddColumn($oColumn3)->AddColumn($oColumn4)
			 ->AddColumn($oColumn5)->AddColumn($oColumn6);
	
	$oAction1 = new Builder_Helper_Action();
	$oAction1->SetName('Add New Company')->SetClass('add');
	
	$oAction2 = new Builder_Helper_Action();
	$oAction2->SetName('Delete Company')->SetClass('delete');
	
	$oActions = new Builder_Helper_Actions();
	$oActions->AddAction($oAction1)->AddAction($oAction2);
	
	// Add our groups to our form helper and set some basic attributes
	$oGridHelper = new Builder_Helper_Grid('company-grid');
	$oGridHelper->AddColumns($oColumns)->AddFooterActions($oActions)
				->SetHeading('Companies')
				->SetSubheading('List of companies that do business in this area grouped by category')
				->SetCopy("Grouping currently does not support ordering as well (it gets messy quickly). But don't worry. It's in the TODO list.")
			    ->SetPaging(array('num-records' => 10))
			    ->SetGroup('category')
				->SetScript('post_to_this_form.php');
	
	// Build our form
	$oGrid = new Builder_Grid();
	echo $oGrid->Render($oGridHelper->ToArray(), $aCompanies);
}
catch (Exception $oException)
{
	echo 'Exception: ' . $oException->getMessage();
}
?>
</body>
</html>