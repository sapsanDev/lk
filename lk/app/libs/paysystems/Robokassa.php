<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/

namespace app\libs\paysystems;

use app\libs\paysystems\Basefunction;

class Robokassa extends Basefunction{

	public function CheckSignature($post){
			$us = $this->Decoder($post['Shp_mysign']);
		 	$this->decod = explode(',', $us);
		 	$checkKassa = $this->checkKassa('Robokassa');
		 	if(empty($checkKassa))exit;
			$sign = strtoupper(md5($post['OutSum'].':'.$post['InvId'].':'.$this->kassa[0]['secret_key_2'].':Shp_mysign='.$post['Shp_mysign']));
			if($sign != strtoupper($post['SignatureValue'])){exit($this->addLog('Robokassa - Не верная цифровая подпись.'));}
	}

	public function getPay($post){
		$checkPay = $this->checkPay('Robokassa');
		 if(empty($checkPay))exit;
		 if($this->decod[2] != $post['OutSum'])
		 	exit($this->addLog('Robokassa - Не совподает сумма : '.$this->decod[2].'/'.$post['OutSum']));
		 $this->checkPlayer();
		 $this->checkPromo('Robokassa');
		 $this->updateBalance($this->decod[3],$post['OutSum']);
		 $this->updatePay();
		 $this->addLog('Robokassa - Пополнение баланса на сумму:'.$post['OutSum'].'руб. SteamID:'.$this->decod[3].' Платеж:#'.$this->decod[1]);
	}
}