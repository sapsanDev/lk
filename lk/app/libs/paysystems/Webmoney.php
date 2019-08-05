<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/

namespace app\libs\paysystems;

use app\libs\paysystems\Basefunction;

class Webmoney extends Basefunction{

	 public function CheckPurse($post){
	 	$us = $this->Decoder($post['lk_sign']);
		 	$this->decod = explode(',', $us);
		 	$checkKassa = $this->checkKassa('WebMoney');
		 	if(empty($checkKassa))exit;
		if ($post['LMI_PREREQUEST'] == 1){
		if ($post['LMI_PAYEE_PURSE'] == $this->kassa[0]['shop_id']) echo 'YES';
		}
	}

	public function CheckSignature($post){
		$key = $post['LMI_PAYEE_PURSE'].$post['LMI_PAYMENT_AMOUNT'].$post['LMI_PAYMENT_NO'].$post['LMI_MODE'].$post['LMI_SYS_INVS_NO'].$post['LMI_SYS_TRANS_NO'].$post['LMI_SYS_TRANS_DATE'].$this->kassa[0]['secret_key_1'].$post['LMI_PAYER_PURSE'].$post['LMI_PAYER_WM'];
		if (strtoupper(hash('sha256', $key)) != $post['LMI_HASH'])exit($this->addLog('WebMoney - Неверная цифровая подпись.'));
	}

	public function getPay($post){
		$checkPay = $this->checkPay('WebMoney');
		 if(empty($checkPay))exit;
		 if($this->decod[2] != $post['LMI_PAYMENT_AMOUNT'])
		 	exit($this->addLog('WebMoney - Не совподает сумма : '.$this->decod[2].'/'.$post['LMI_PAYMENT_AMOUNT']));
		 $this->checkPlayer();
		 $this->checkPromo('WebMoney');
		 $this->updateBalance($this->decod[3],$post['LMI_PAYMENT_AMOUNT']);
		 $this->updatePay();
		 $this->addLog('WebMoney - Пополнение баланса на сумму:'.$post['LMI_PAYMENT_AMOUNT'].'руб. SteamID:'.$this->decod[3].' Платеж:#'.$this->decod[1]);
	}

}