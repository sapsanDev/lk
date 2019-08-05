<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/

namespace app\libs\paysystems;

use app\libs\paysystems\Basefunction;

class Interkassa extends Basefunction{

	public function CheckSignature($post){
			$us = $this->Decoder($post['ik_x_sign']);
		 	$this->decod = explode(',', $us);
		 	$checkKassa = $this->checkKassa('InterKassa');
		 	if(empty($checkKassa))exit;
		 	$dataSet = $post;
			unset($dataSet['ik_sign']);
			ksort($dataSet, SORT_STRING);
			array_push($dataSet, $this->kassa[0]['secret_key_1']);
			$signString = implode(':', $dataSet);
			$sign = base64_encode(md5($signString, true));
			if($sign != $post['ik_sign']){exit($this->addLog('InterKassa - Не верная цифровая подпись.'));}
	}

	public function getPay($post){
		$checkPay = $this->checkPay('InterKassa');
		 if(empty($checkPay))exit;
		 if($this->decod[2] != $post['ik_am'])
		 	exit($this->addLog('InterKassa - Не совподает сумма : '.$this->decod[2].'/'.$post['ik_am']));
		 $this->checkPlayer();
		 $this->checkPromo('InterKassa');
		 $this->updateBalance($this->decod[3],$post['ik_am']);
		 $this->updatePay();
		  $this->addLog('InterKassa - Пополнение баланса на сумму:'.$post['ik_am'].'руб. SteamID:'.$this->decod[3].' Платеж:#'.$this->decod[1]);
	}
}