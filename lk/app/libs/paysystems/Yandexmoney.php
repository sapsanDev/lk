<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/

namespace app\libs\paysystems;

use app\libs\paysystems\Basefunction;

class Yandexmoney extends Basefunction{

	public function CheckSignature($post){
		$us = $this->Decoder($post['label']);
		$this->decod = explode(',', $us);
		$checkKassa = $this->checkKassa('YandexMoney');
		if(empty($checkKassa))exit;
		$hash = sha1($post['notification_type'].'&'.$post['operation_id'].'&'.$post['amount'].'&'.$post['currency'].'&'.$post['datetime'].'&'.$post['sender'].'&'.$post['codepro'].'&'.$this->kassa[0]['secret_key_1'].'&'.$post['label']);
		if($post['sha1_hash'] != $hash or $post['codepro'] === true or $post['unaccepted'] === true )exit($this->addLog('YandexMoney - Неверная цифровая подпись.'));
	}

	public function getPay($post){
		$checkPay = $this->checkPay('YandexMoney');
		 if(empty($checkPay))exit;
		 if($this->decod[2] != $post['withdraw_amount'])
		 	exit($this->addLog('YandexMoney - Не совподает сумма : '.$this->decod[2].'/'.$post['withdraw_amount']));
		 $this->checkPlayer();
		 $this->checkPromo('YandexMoney');
		 $this->updateBalance($this->decod[3],$post['withdraw_amount']);
		 $this->updatePay();
		 $this->addLog('YandexMoney - Пополнение баланса на сумму:'.$post['withdraw_amount'].'руб. SteamID:'.$this->decod[3].' Платеж:#'.$this->decod[1]);
	}

}