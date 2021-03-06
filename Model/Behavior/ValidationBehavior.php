<?php
class ValidationBehavior extends ModelBehavior {
	function beforeValidate(Model $model, $options = array()) {
		$model->validate['recaptcha_response_field'] = array(
			'checkRecaptcha' => array(
				'rule' => array('checkRecaptcha', 'recaptcha_challenge_field'),
				'required' => true,
				'allowEmpty' => false,
				'message' => "Your response did not match the verification image.  Please try again.",
			),
		);
		return true;
	}

	function checkRecaptcha(Model $model, $data, $target) {
		App::import('Vendor', 'Recaptcha.recaptchalib');
		$privatekey = Configure::read('Recaptcha.Private');
		$res = recaptcha_check_answer(
			$privatekey, 							$_SERVER['REMOTE_ADDR'],
			$model->data[$model->alias][$target], 	$data['recaptcha_response_field']
		);
		return $res->is_valid;
	}
}
