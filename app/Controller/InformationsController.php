<?php
class InformationsController extends AppController{

	public function index(){
		if($this->Auth->user('role') > 0){
			$this->set('data', $this->Informations->find('first', ['conditions' => ['Informations.id' => 1]]));
		}
		else{
			throw new NotFoundException();
		}
	}

	public function updateInformations(){
		if($this->Auth->user('role') > 0){
			if($this->request->is('post')){
				$this->Informations->id = 1;
				if($this->Informations->save($this->request->data)){
					$this->Session->setFlash('Informations modifiées !', 'success');
				}
				return $this->redirect(['controller' => 'informations', 'action' => 'index']);
			}
		}
		else{
			throw new NotFoundException();
		}
	}

	public function testJsonapi(){
		if($this->Auth->user('role') > 0){
			if($this->request->is('ajax')){
				sleep(2);
		    	$api = new JSONAPI($this->request->data['ip'], $this->request->data['port'], $this->request->data['username'], $this->request->data['password'], $this->request->data['salt']);
				if($api->call('players.online.limit')[0]['result'] == 'success'){
					$result = 'success';
				}
				else{
					$result = 'failure';
				}
				echo json_encode(['result' => $result]);
				exit();
			}
		}
		else{
			throw new NotFoundException();
		}
	}

	public function updateOptions(){
		if($this->Auth->user('role') > 0){
			if($this->request->is('post')){
				$this->Informations->id = 1;
				if(isset($this->request->data['use_store'])){
					$this->Informations->saveField('use_store', 1);
				}
				else{
					$this->Informations->saveField('use_store', 0);
				}
				if(isset($this->request->data['use_paypal'])){
					$this->Informations->saveField('use_paypal', 1);
				}
				else{
					$this->Informations->saveField('use_paypal', 0);
				}
				if(isset($this->request->data['use_economy'])){
					$this->Informations->saveField('use_economy', 1);
				}
				else{
					$this->Informations->saveField('use_economy', 0);
				}
				if(isset($this->request->data['use_server_money'])){
					$this->Informations->saveField('use_server_money', 1);
				}
				else{
					$this->Informations->saveField('use_server_money', 0);
				}
				if(isset($this->request->data['use_team'])){
					$this->Informations->saveField('use_team', 1);
				}
				else{
					$this->Informations->saveField('use_team', 0);
				}
				if(isset($this->request->data['use_contact'])){
					$this->Informations->saveField('use_contact', 1);
				}
				else{
					$this->Informations->saveField('use_contact', 0);
				}
				if(isset($this->request->data['use_rules'])){
					$this->Informations->saveField('use_rules', 1);
				}
				else{
					$this->Informations->saveField('use_rules', 0);
				}
				if(isset($this->request->data['happy_hour'])){
					$informations = $this->Informations->find('first');
			    	$api = new JSONAPI($informations['Informations']['jsonapi_ip'], $informations['Informations']['jsonapi_port'], $informations['Informations']['jsonapi_username'], $informations['Informations']['jsonapi_password'], $informations['Informations']['jsonapi_salt']);
					$api->call('server.run_command', ['say Happy hour activé, rendez-vous sur le site. '.$informations['Informations']['happy_hour_bonus'].'% de '.$informations['Informations']['site_money'].' gratuits ! (http://'.$_SERVER['HTTP_HOST'].$this->webroot.'recharger)']);
					$this->Informations->saveField('happy_hour', 1);
				}
				else{
					$this->Informations->saveField('happy_hour', 0);
				}
				$this->Session->setFlash('Options mises à jour !', 'success');
				return $this->redirect(['controller' => 'informations', 'action' => 'index']);
			}
		}
		else{
			throw new NotFoundException();
		}
	}
}