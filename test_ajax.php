
<?php 
require_once '_src/models/AjaxModel.php';

		$ajaxModel = new AjaxModel();
		$result = $ajaxModel->setAjaxReview();
var_dump($result);
