<?php

namespace app\models;

use app\models\DbModel;
use app\models\Admin;

class Ajax extends DbModel{
	
	public function pay($post){
		$Admin = new Admin;
		$Kassa = $Admin->satKassaOn();
		if(empty($Kassa))
			$this->message('Прости браток, но кажется Админ забыл настроить кассу для оплаты ¯\_(ツ)_/¯','Error');
		else if(empty($post['kassa']))
				$this->message('Ты платежную систему то выбери!','Error');
		else if(!preg_match('/^\d+$/i', $post['kassa']))
				$this->message('Миша все хуйня! Давай по новой!','Error');
		else if(empty($post['summ']))
				$this->message('И как же ты собрался без денег?','Error');
		else if(!preg_match('/^[0-9]{1,5}.[0-9]{1,2}$/', $this->WM($post['summ'])))
				$this->message('Ну чувак, целое число 10 либо с точкой 10.84!','Error');
		else if($post['summ'] < 10)
				$this->message('Минимум червонец браток!','Error');
		else if(empty($post['steam']))
				$this->message('Я не Ванга Steam ID твой не узнаю!','Error');
		else if(!preg_match('/STEAM_[0-9]{1,2}:[0-1]:\d+/i',$post['steam']))
				$this->message('Что ты мне какую-то дичь загоняешь, а не свой Steam ID!','Error');
		else if(!empty($post['promo'])){
			if(!preg_match('/^[A-z-0-9]{5,15}$/',$post['promo']))
				$this->message('Это что промокоды такие стали давать? Введи нормальный!','Error');
			$this->checkPromo($post['promo'],$post['steam']);
		}
		$Admin->existsKassa($post['kassa']);
		$this->setPay($post);
	}

	protected function setPay($post){
		$data = $this->DataKassa($post['kassa']);
		$order = time() % 100000;
		$desc = 'LK WEB - Пополнение баланса для '.$post['steam'];
		$lk_sign = $this->Encoder($post['kassa'].','.$order.','.$post['summ'].','.$post['steam']);
		switch ($post['kassa']) {
			case 1:
				if(empty($data['status']))
					$this->message('Способ оплаты через FreeKassa выключен Администратором','Error');
				$this->recPay($order,$post,'FreeKassa');
				$sign = md5($data['shop_id'].':'.$post['summ'].':'.$data['secret_key_1'].':'.$order);
				$this->location('http://www.free-kassa.ru/merchant/cash.php?m='.$data['shop_id'].'&oa='.$post['summ'].'&o='.$order.'&s='.$sign.'&us_sign='.$lk_sign);
				break;
			case 2:
				if(empty($data['status']))
					$this->message('Способ оплаты через InterKassa выключен Администратором','Error');
					$this->recPay($order,$post,'InterKassa');
					$this->message('<form  method="post" action="https://sci.interkassa.com/"><input name="ik_co_id" value="'.$data['shop_id'].'"><input name="ik_pm_no" value="'.$order.'"><input name="ik_x_sign" value="'.$lk_sign.'"><input name="ik_cur" value="RUB"><input name="ik_desc" value="'.$desc.'"><input name="ik_am"  value="'.$post['summ'].'"><input id="punsh" type="submit"></form>','');
				break;
			case 3:
				if(empty($data['status']))
					$this->message('Способ оплаты через RoboKassa выключен Администратором','Error');
					$this->recPay($order,$post,'RoboKassa');
					$sign = md5($data['shop_id'].':'.$post['summ'].':'.$order.':'.$data['secret_key_1'].':Shp_mysign='.$lk_sign);
					$this->message('<form action="https://merchant.roboxchange.com/Index.aspx" method=POST><input name="MrchLogin" value="'.$data['shop_id'].'"><input name="OutSum" value="'.$post['summ'].'"><input name="SignatureValue" value="'.$sign.'"><input name="InvId" value="'.$order.'"><input name="Desc" value="'.$desc.'"><input name="Shp_mysign" value="'.$lk_sign.'"><input id="punsh" type="submit" ></form>','');
				break;
			case 4:
				if(empty($data['status']))
					$this->message('Способ оплаты через UnitPay выключен Администратором','Error');
					$this->recPay($order,$post,'UnitPay');
					$sign = hash('sha256', $lk_sign.'{up}RUB{up}'.$desc.'{up}'.$post['summ'].'{up}'.$data['secret_key_2']);
					$this->message('<form action="https://unitpay.ru/pay/'.$data['secret_key_1'].'" method=GET><input name="sum" value="'.$post['summ'].'">
						<input name="account" value="'.$lk_sign.'"><input name="signature" value="'.$sign.'"><input name="desc" value="'.$desc.'"><input name="currency" value="RUB"><input id="punsh" type="submit" ></form>','');
				break;
			case 6:
				if(empty($data['status']))
					$this->message('Способ оплаты через YandexMoney выключен Администратором','Error');
					$this->recPay($order,$post,'YandexMoney');
					$lk_sign = $this->Encoder($post['kassa'].','.$order.','.$post['summ'].','.$post['steam']);
					$this->message('<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml"> 
    				<input name="receiver" value="'.$data['shop_id'].'"><input name="quickpay-form" value="shop"><input name="targets" value="'.$desc.'"> 
    				<input name="paymentType" value="PC"><input name="label" value="'.$lk_sign.'"> 
    				<input name="sum" value="'.$post['summ'].'"><input id="punsh" type="submit"></form>','');
				break;
			case 7:
				if(empty($data['status']))
					$this->message('Способ оплаты через YandexMoney(карта) выключен Администратором','Error');
					$this->recPay($order,$post,'YandexMoney(карта)');
					$lk_sign = $this->Encoder($post['kassa'].','.$order.','.$post['summ'].','.$post['steam']);
					$this->message('<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml"> 
    				<input name="receiver" value="'.$data['shop_id'].'"><input name="quickpay-form" value="shop"><input name="targets" value="'.$desc.'"> 
    				<input name="paymentType" value="AC"><input name="label" value="'.$lk_sign.'"> 
    				<input name="sum" value="'.$post['summ'].'"><input id="punsh" type="submit"></form>','');
				break;
			case 8:
				if(empty($data['status']))
					$this->message('Способ оплаты через WebMoney выключен Администратором','Error');
					$this->recPay($order,$post,'WebMoney');
					$this->message('<form action="https://merchant.webmoney.ru/lmi/payment.asp" method="POST">
 					<input name="lk_sign" value="'.$lk_sign.'"><input name="LMI_PAYMENT_DESC_BASE64" value="'.base64_encode($desc).'"><input name="LMI_PAYMENT_NO" value="'.$order.'">
 					<input name="LMI_PAYEE_PURSE" value="'.$data['shop_id'].'"><input name="LMI_PAYMENT_AMOUNT" value="'.$post['summ'].'"><input id="punsh" type="submit"></form>','');
				break;	
			case 9:
				if(empty($data['status']))
					$this->message('Способ оплаты через PayPal выключен Администратором','Error');
					$this->recPay($order,$post,'PayPal');
					$lk_sign = $this->Encoder($post['kassa'].','.$order.','.$this->WM($post['summ']).','.$post['steam']);
					$this->message('<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_xclick">
									  <input type="hidden" name="business" value="'.$data['shop_id'].'"><input type="hidden" name="notify_url" value="'.$data['secret_key_1'].'"><input type="hidden" name="item_name" value="'.$desc.'"><input type="hidden" name="item_number" value="'.$lk_sign.'">
									  <input type="hidden" name="amount" value="'.$this->WM($post['summ']).'"><input type="hidden" name="currency_code" value="EUR"><input id="punsh" type="submit" name="submit"></form>','');
			case 10:
				if(empty($data['status']))
					$this->message('Способ оплаты через Qiwi выключен Администратором','Error');
					$this->recPay($order,$post,'Qiwi');
					$lk_sign = $this->Encoder($post['kassa'].','.$order.','.$this->WM($post['summ']).','.$post['steam']);
					$this->message('<form action="https://oplata.qiwi.com/create" method="GET"><input type="hidden" name="publicKey" value="'.$data['secret_key_1'].'"><input type="hidden" name="comment" value="'.$desc.'"><input type="hidden" name="account" value="'.$lk_sign.'"><input type="hidden" name="amount" value="'.$this->WM($post['summ']).'"><input type="hidden" name="successUrl" value="http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'">
						<input id="punsh" type="submit" name="submit"></form>','');
				break;			
			
			default:
				$this->message('Ошибка','Error');
				break;
		}

	}

	protected function checkPromo($promo,$steam){
		$param = ['code' => $promo];
		$codeInfo = $this->db->row('SELECT * FROM lk_promocodes WHERE code = :code', $param);
		if(empty($codeInfo))
			$this->message('Уверен что правильно ввел? Что-то я такого промокода не нашел!','Error');
		else if($codeInfo[0]['attempts'] <= 0)
			$this->message('Прости братан, но лимит кода исчерпан!','Error');
		else if($codeInfo[0]['auth1']){
			preg_match('/:[0-9]{1}:\d+/i', $steam, $auth);
			$params = ['code'=>$promo,'auth' => '%'.$auth[0].'%'];
			$userPromo = $this->db->one('SELECT pay_promo FROM lk_pays WHERE pay_promo=:code AND pay_status = 1 AND pay_auth LIKE :auth', $params);
			if($userPromo)
				$this->message('Братан ну не наглей, ты же уже вводил этот промокод!','Error');
		}
	}

	public function calcPromo($promo,$steam,$summ){
		if($summ < 10)exit (trim(json_encode(array(
							'result' => '<div class="mess_error">Минимум червонец браток!</div>'
						))));

		else if(!preg_match('/STEAM_[0-9]{1,2}:[0-1]:\d+/i',$steam))
				exit (trim(json_encode(array(
							'result' => '<div class="mess_error">😡 Хватит вбивать в SteamID всякую дичь! 😡</div>'
						))));
		else if($summ >=10 && !empty($steam)){
			$param = ['code' => $promo];
			$codeInfo = $this->db->row('SELECT * FROM lk_promocodes WHERE code = :code', $param);
			if(empty($codeInfo))
				exit (trim(json_encode(array(
							'result' => '<div class="mess_warn">🙅‍♂ Миша все хуйня! Давай по новой промокод! 🙅‍♂</div>'
						))));
			else if($codeInfo[0]['attempts'] <= 0)
				exit (trim(json_encode(array(
							'result' => '<div class="mess_warn">Прости братан, но лимит промокода исчерпан!<br>¯\_(ツ)_/¯</div>'
						))));
			else if($codeInfo[0]['auth1']){
				preg_match('/:[0-9]{1}:\d+/i', $steam, $auth);
				$params = ['code'=>$promo,'auth' => '%'.$auth[0].'%'];
				$userPromo = $this->db->one('SELECT pay_promo FROM lk_pays WHERE pay_promo=:code AND pay_status = 1 AND pay_auth LIKE :auth', $params);
				if($userPromo)
					exit (trim(json_encode(array(
							'result' => '<div class="mess_error">🤦Бля ну не наглей, ты уже вводил этот промокод!🤦</div>'
						))));
			}
			$bonus = ($summ/100)*$codeInfo[0]['percent'];
			$nsumm = $bonus+$summ;
			exit (trim(json_encode(array(
						'result' => '<div class="mess_good">С промокодом Вы получите <b style="color:green">'.$nsumm.'</b> руб. на баланс!<br>
									Промокод дает <b style="color:green">+'.$codeInfo[0]['percent'].'%</b> к сумме пополнения!</div>'
					))));

		}
	}

	protected function recPay($order,$post,$system){
		$params = ['order'		=> $order,
					'auth'		=> $post['steam'],
					'summ'		=> $post['summ'],
					'data'		=> date('d.m.Y в H:i:s'),
					'system'	=> $system,
					'promo'		=> $post['promo'],
					'confirm'	=> 0,
				];
		$this->db->query('INSERT INTO lk_pays (pay_order, pay_auth, pay_summ, pay_data, pay_system, pay_promo, pay_status) VALUES(:order,:auth,:summ,:data,:system,:promo,:confirm)',$params);
	}

	protected function DataKassa($post){
			$param =['id'=> $post];
		$server = $this->db->row('SELECT * FROM lk_pay_service WHERE id = :id', $param);
		if(empty($server ))
			$this->message('Такой кассы не существует','Error');
		return $server[0];
	}
	protected function Encoder($string){
		$return = base64_encode(base64_encode($string));
		return $return;
	}
protected function WM($summ){
	$ita = explode('.', $summ);
	if(COUNT($ita) == 1){
		$summa = $ita[0].'.00';
	}else{
		$summa = $summ;
	}
	return $summa;
}

}