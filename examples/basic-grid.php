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
			'description'   => 'Company A provides a service'
		),
		array(
			'id'            => 2,
			'name'          => 'Company B',
			'category'      => 'Manufactorer',
			'description'   => 'Company B makes plastic cup holders'
		),
		array(
	        'id'            => 3,
	        'name'          => 'Company C',
	        'category'      => 'Retail',
	        'description'   => 'Company C sells the items that Company B makes'
		),
		array(
	        'id'            => 4,
	        'name'          => 'Company D',
	        'category'      => 'Service',
	        'description'   => 'Company D provides a service'
		),
		array(
	        'id'            => 5,
	        'name'          => 'Company E',
	        'category'      => 'Manufactorer',
			'description'   => 'Company E makes plastic cup holders'
		),
		array(
			'id'            => 6,
			'name'          => 'Company F',
			'category'      => 'Retail',
			'description'   => 'Company F sells the items that Company E makes'
		),
		array(
			'id'            => 7,
			'name'          => 'Company G',
			'category'      => 'Service',
			'description'   => 'Company G provides a service'
		),
		array(
			'id'            => 8,
			'name'          => 'Company H',
			'category'      => 'Manufactorer',
			'description'   => 'Company H makes plastic cup holders'
		),
		array(
			'id'            => 9,
			'name'          => 'Company I',
			'category'      => 'Retail',
			'description'   => 'Company I sells the items that Company H makes'
		),
		array(
			'id'            => 10,
			'name'          => 'Company J',
	        'category'      => 'Service',
	        'description'   => 'Company J provides a service'
		),
		array(
			'id'            => 11,
			'name'          => 'Company K',
	        'category'      => 'Manufactorer',
	        'description'   => 'Company K makes plastic cup holders'
		),
		array(
			'id'            => 12,
			'name'          => 'Company L',
			'category'      => 'Retail',
			'description'   => 'Company L sells the items that Company K makes'
		),
	);
	
	$oColumn1 = new Builder_Helper_Column();
	$oColumn1->SetName('id')->SetType('checkbox');
	
	$oColumn2 = new Builder_Helper_Column();
	$oColumn2->SetName('id')->SetLabel('Id')
	         ->SetSort('numeric');
	
	$oParam = new Builder_Helper_Link_Param();
	$oParam->SetName('id')->SetKey('id');
	$oLink = new Builder_Helper_Link();
	$oLink->SetPath(BuildUrl('company.php'))->SetParam($oParam);
	
	$oColumn3 = new Builder_Helper_Column();
	$oColumn3->SetName('name')->SetLabel('Name')
			 ->SetSort('alpha')->SetLink($oLink);
	
	$oColumn4 = new Builder_Helper_Column();
	$oColumn4->SetName('category')->SetLabel('Category')
			 ->SetSort('alpha');
	
	$oColumn5 = new Builder_Helper_Column();
	$oColumn5->SetName('description')->SetLabel('Description')
			 ->SetFormat('truncate|40,...');
	
	$oColumns = new Builder_Helper_Columns();
	$oColumns->AddColumn($oColumn1)->AddColumn($oColumn2)
			 ->AddColumn($oColumn3)->AddColumn($oColumn4)
			 ->AddColumn($oColumn5);
	
	$oAction1 = new Builder_Helper_Action();
	$oAction1->SetName('Add New Company')->SetClass('add');
	
	$oAction2 = new Builder_Helper_Action();
	$oAction1->SetName('Delete Company')->SetClass('delete');
	
	$oActions = new Builder_Helper_Actions();
	$oActions->AddAction($oAction1)->AddAction($oAction2);
	
	
	// Add our groups to our form helper and set some basic attributes
	$oGridHelper = new Builder_Helper_Grid('company-grid');
	$oGridHelper->AddColumns($oColumns)->AddFooterActions($oActions)
				->SetHeading('Companies')
			    ->SetPaging(array('num-records' => 10))
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