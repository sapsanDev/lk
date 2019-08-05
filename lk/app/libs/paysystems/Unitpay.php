<?php 
/**
* LK WEB
* @author SAPSAN 隼
*
*/

namespace app\libs\paysystems;

use app\libs\paysystems\Basefunction;

class Unitpay extends Basefunction{

	public function payerUnit($method,$params){
			$this->CheckIP();
			$us = $this->Decoder($params['account']);
		 	$this->decod = explode(',', $us);
		 	$checkKassa = $this->checkKassa('UnitPay');
		 	if(empty($checkKassa)){
			    $result = array('error' => array('message' => 'UnitPay - Данная система выключена либо не используется!'));
			    $this->hardReturnJson($result);
			 }
		 	if($this->getSignature($method, $params, $this->kassa[0]['secret_key_2']) != $params['signature']){
				$this->addLog('UnitPay - Не верная цифровая подпись.');
				$result = array('error' => array('message' => 'Не верная цифровая подпись!'));
				$this->hardReturnJson($result);
			}else{ 
					switch ($method){
			            case 'check':
			                $this->checkPlayer();
			                $checkPay = $this->checkPay($this->decod[3],$params['orderSum']);
			                if(empty($checkPay)){
			                    $result = array('error' => array('message' => 'Платеж #'.$this->decod[1].' уже оплачен или не существует'));
			                }
			                else $result = array('result' => array('message' => 'Запрос успешно обработан'));
			                $this->hardReturnJson($result);
			                break;
			            case 'pay':
			            	$this->checkPromo('UnitPay');
			                $this->updateBalance($this->decod[3],$params['orderSum']);
			                $this->updatePay();
			                $this->addLog('UnitPay - Пополнение баланса на сумму:'.$params['orderSum'].'руб. SteamID:'.$this->decod[3].' Платеж:#'.$this->decod[1]);
			                $result = array('result' => array('message' => 'Запрос успешно обработан'));
			                $this->hardReturnJson($result);
			                break;
			            case 'error':
			                $result = array('result' => array('message' => 'Запрос успешно обработан'));
			                $this->hardReturnJson($result);
			                break;
			            default:
			                $result = array('error' => array('message' => 'неверный метод'));
			                $this->hardReturnJson($result);
			                break;
			        }
		    }
	}

	public function getSignature($method, array $data, $secretKey) {
			ksort($data);
			unset($data['sign']);
			unset($data['signature']);
			array_push($data, $secretKey);
			array_unshift($data, $method);
	   		return hash('sha256', join('{up}', $data));
	}

	public function hardReturnJson( $arr ){
		    header('Content-Type: application/json');
		    $result = json_encode($arr);
		    die($result);
	}

	public function CheckIP(){
		if(!in_array($this->getIP(),
			array('31.186.100.49','178.132.203.105','52.29.152.23','52.19.56.234','35.196.167.40')))
			exit($this->addLog('UnitPay - Запрос с запрещенного IP.'.$this->getIP()));
	}

	protected function getIP(){
			if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
			return $_SERVER['REMOTE_ADDR'];
	}
}