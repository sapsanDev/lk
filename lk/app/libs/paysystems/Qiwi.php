<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/

namespace app\libs\paysystems;

use app\libs\paysystems\Basefunction;

class Qiwi extends Basefunction{

	public function getPay($post, $signature){
		$us = $this->Decoder($post['bill']['customer']['account']);
		 $this->decod = explode(',', $us);
		 $checkKassa = $this->checkKassa('Qiwi');
		 $invoice_parameters = $post['bill']['amount']['currency'].'|'.$post['bill']['amount']['value'].'|'.$post['bill']['billId'].'|'.$post['bill']['siteId'].'|'.$post['bill']['status']['value'];
		 $sign = hash_hmac('sha256', $invoice_parameters,$this->kassa[0]['secret_key_2']);
		 if($sign == $signature){
			 if($post['bill']['status']['value'] == 'PAID'){
				 $checkPay = $this->checkPay('Qiwi');
				 if(empty($checkPay)){
				 	header("Content-Type: application/json");
					die('{"error":"1"}');

				 }
				 if($this->decod[2] != $post['bill']['amount']['value']){
				 	$this->addLog('Qiwi - Не совподает сумма : '.$this->decod[2].'/'.$post['bill']['amount']['value']);
				 	header("Content-Type: application/json");
					die('{"error":"2"}');
				 }
				 $this->checkPlayer();
				 $this->checkPromo('Qiwi');
				 $this->updateBalance($this->decod[3],$post['bill']['amount']['value']);
				 $this->updatePay();
				 $this->addLog('Qiwi - Пополнение баланса на сумму:'.$post['bill']['amount']['value'].'руб. SteamID:'.$this->decod[3].' Платеж:#'.$this->decod[1]);
				 header("Content-Type: application/json");
				 echo '{"error":"0"}';
		 	}else {
		 		$this->addLog('Qiwi - Статус платежа не оплачен! Состояние: '.$post['bill']['status']['value']);
		 		header("Content-Type: application/json");
				die('{"error":"3"}');
		 	}
		}else {
		 		$this->addLog('Qiwi - Не верная сигнатура');
		 		header("Content-Type: application/json");
				die('{"error":"4"}');
		 	}
	}
}

