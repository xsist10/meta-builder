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
	$aMeta = array(
	    'identity' => 'company-add-form',
	    'groups' => array(
	        array(
	            'heading'       => 'Create New Company',
	            'subheading'    => 'Use this tool to create a new company entry.',
	            'rows'          => array(
	                array(
	                    'label'     => 'Name',
	                    'element'   => array(
	                        array(
	                            'name'  => 'name',
	                            'type'  => 'text',
	                            'value' => 'Prepopulated Field',
	                            'size'  => 30,
	                            'validation'    => array(
	                                'required'      => true,
	                                'length'        => array(
	                                    'min'   => 2,
	                                    'max'   => 255
	                                )
	                            )
	                        ),
	                    )
	                ),
	                array(
	                    'label'         => 'Registration No',
	                    'image'         => '16x16/report.png',
	                    'element'       => array(
	                        array(
	                            'name'  => 'registration_no',
	                            'description'   => '<br/>Your allocated company registration number',
	                            'size'   => 20,
	                            'type'  => 'text',
	                            'validation'    => array(
	                                'length'        => array(
	                                    'min'   => 10,
	                                    'max'   => 32
	                                )
	                            )
	                        ),
	                    )
	                ),
	                array(
	                    'label'         => 'Tax Registration No',
	                    'image'         => '16x16/money-coin.png',
	                    'element'       => array(
	                        array(
	                            'name'          => 'tax_registration_no',
	                            'description'   => '<br/>Your tax registration number (if applicable)',
	                            'type'          => 'text',
	                            'size'          => 20,
	                            'validation'    => array(
	                                'length'        => array(
	                                    'min'   => 6,
	                                    'max'   => 255
	                                )
	                            )
	                        ),
	                    )
	                ),
	                array(
	                    'label'         => 'Image/Logo',
	                    'image'         => '16x16/image.png',
	                    'element'       => array(
	                        array(
	                            'name'          => 'registered_as',
	                            'size'          => 30,
	                            'description'   => '<br/>Upload an image or logo you would like displayed.',
	                            'type'          => 'file',
	                        ),
	                    )
	                ),
	                array(
	                    'label'         => 'Category',
	                    'image'         => '16x16/block.png',
	                    'element'       => array(
	                        array(
	                            'name'          => 'category_id',
	                            'type'          => 'list',
	                            'description'   => '<br/>Select the category that best matches your company.',
	                            'render-mode'   => 'select',
	                            'list'          => array(
	                                array(
	                                    'value' => 'Service',
	                                    'name' => 'Service',
	                                ),
	                                array(
	                                    'value' => 'Manufacturers',
	                                    'name' => 'Manufacturers',
	                                ),
	                                array(
	                                    'value' => 'Retail',
	                                    'name' => 'Retail',
	                                )
	                            )
	                        ),
	                    )
	                ),
	                array(
	                    'label'         => 'Website',
	                    'image'         => '16x16/application-browser.png',
	                    'element'       => array(
	                        array(
	                            'name'          => 'website',
	                            'description'   => '<br/>The companies website address (if applicable)',
	                            'type'          => 'text',
	                            'size'          => 30,
	                            'validation'    => array(
	                                'match'         => 'url',
	                                'length'        => array(
	                                    'min'   => 6,
	                                    'max'   => 255
	                                )
	                            )
	                        ),
	                    )
	                ),
	                array(
	                    'label'         => 'Description',
	                    'element'       => array(
	                        array(
	                            'name'          => 'description',
	                            'type'          => 'textarea',
	                            'cols'          => 37,
	                            'rows'          => 10,
	                            'description'   => '<br/>Provide a long, indepth description for this company.',
	                        ),
	                    )
	                ),
	                array(
	                    'element'   => array(
	                        array(
	                            'name'          => 'action',
	                            'type'          => 'submit',
	                            'value'         => 'Add Company'
	                        )
	                    )
	                )
	            )
	        )
	    )
	);
    
    // Build our form
    $oForm = new Builder_Form();
    echo $oForm->Render($aMeta); 
}
catch (Exception $oException)
{
	echo 'Exception: ' . $oException->getMessage();
}
?>
</body>
</html>