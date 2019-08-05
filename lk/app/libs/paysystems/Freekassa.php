<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/

namespace app\libs\paysystems;

use app\libs\paysystems\Basefunction;

class Freekassa extends Basefunction{

	public function CheckIP(){
		if(!in_array($this->getIP(), array(
						'136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98', '136.243.38.108'
					)))exit($this->addLog('FreeKassa - Запрос с запрещенного IP.'.$this->getIP()));
	}

	public function CheckSignature($post){
			$us = $this->Decoder($post['us_sign']);
		 	$this->decod = explode(',', $us);
		 	$checkKassa = $this->checkKassa('FreeKassa');
		 	if(empty($checkKassa))exit;
			$sign = md5($this->kassa[0]['shop_id'] .':'.$post['AMOUNT'].':'.$this->kassa[0]['secret_key_2'].':'.$post['MERCHANT_ORDER_ID']);
			if($sign != $post['SIGN']){exit($this->addLog('FreeKassa - Не верная цифровая подпись.'));}
	}

	public function getPay($post){
		$checkPay = $this->checkPay('FreeKassa');
		 if(empty($checkPay))exit;
		 if($this->decod[2] != $post['AMOUNT'])
		 	exit($this->addLog('FreeKassa - Не совподает сумма : '.$this->decod[2].'/'.$post['AMOUNT']));
		 $this->checkPlayer();
		 $this->checkPromo('FreeKassa');
		 $this->updateBalance($this->decod[3],$post['AMOUNT']);
		 $this->updatePay();
		 $this->addLog('FreeKassa - Пополнение баланса на сумму:'.$post['AMOUNT'].'руб. SteamID:'.$this->decod[3].' Платеж:#'.$this->decod[1]);
		 die('YES');
	}

	protected function getIP(){
			if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
			return $_SERVER['REMOTE_ADDR'];
	}

}

