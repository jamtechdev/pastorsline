<?php

namespace App\Controller;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Cache\Cache;
use Cake\Routing\Router;

  

class ApisController extends AppController
{
   
  public $helpers = ['Form'];

	public function initialize(){
		parent::initialize();
		$this->loadComponent('RequestHandler');
		$this->loadComponent('Paginator');

    $this->cors();

	}


	public function beforeFilter(Event $event){
		parent::beforeFilter($event);
	}


public function cors(){
    // Allow from any origin
    if(isset($_SERVER['HTTP_ORIGIN'])){
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    // Access-Control headers are received during OPTIONS requests
    if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
        if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
            if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
               header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}



public function getContacts(){
   // only get method allowed
   $this->request->allowMethod('get');

   // get all contact data with pagination
   $settings = [
     'limit' => 50,
     'order' => ['Contacts.id' => 'asc'],
     'fields' => ['id','first_name','last_name', 'phone_number']
   ];

   $res = $this->paginate('Contacts',$settings);
   $paging= $this->Paginator->getPagingParams();

   
   $this->set([
        'totalRecord' => $paging['Contacts']['count'],
        'perPage' => $paging['Contacts']['perPage'],
        'currentPage' => $paging['Contacts']['page'],
        'totalPage' => $paging['Contacts']['pageCount'],
        'res' => $res,
        '_serialize' => ['totalRecord','perPage','currentPage','totalPage','res']
   ]);
}


public function getContactWithCompany(){
   // only get method allowed
   $this->request->allowMethod('get');

   // get all contact data with pagination
   $settings = [
     'limit' => 50,
     'order' => ['Contacts.id' => 'asc'],
     'fields' => ['Contacts.id','Contacts.first_name','Contacts.last_name', 'Contacts.phone_number','Companies.id','Companies.company_name','Companies.address']
   ];

   $this->paginate = ['contain' => ['Companies']];
   $res = $this->paginate('Contacts',$settings);
   $paging= $this->Paginator->getPagingParams();
   
   $this->set([
        'totalRecord' => $paging['Contacts']['count'],
        'perPage' => $paging['Contacts']['perPage'],
        'currentPage' => $paging['Contacts']['page'],
        'totalPage' => $paging['Contacts']['pageCount'],
        'res' => $res,
        '_serialize' => ['totalRecord','perPage','currentPage','totalPage','res']
   ]);
}


public function store(){
   // only post method allowed
   $this->request->allowMethod('post');
   
   $data= TableRegistry::get('Contacts')->newEntity($this->request->data);

   if($data->errors()) {
       $this->set(['errors' => $data->errors(),'_serialize' => ['errors']]);
       return;
   }

   // get last id
   $max_id = TableRegistry::get('Contacts')->find()->select(['id'])->order(['id' => 'DESC'])->first();
   $data->id = $max_id->id+1;
   
   if(TableRegistry::get('Contacts')->save($data)){
       $this->set(['message' => 'Contact saved successfully','_serialize' => ['message']]);
   }else{
      $this->set(['errors' => 'Contact could not be saved','_serialize' => ['errors']]);
   }

}







}

?>