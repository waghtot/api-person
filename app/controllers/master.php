<?php 

class Master extends Controller
{


    public function __construct(){
        return $this->index();
    }

    public function index(){

        $this->setRequest();

        if($this->getRequest() !== false){


            $data = $this->getRequest();

            if(isset($data->action))
            {
                $response = false;
                switch($data->action)
                {
                    case 'Create Person';
                        if($this->verifyData()!==false)
                        {
                            $response = $this->createPerson();
                        }else{
                            $response = false;
                        }
                    break;
                    case 'Update Person':
                        if($this->verifyData()!==false)
                        {
                            $response = $this->updatePerson();
                        }else{
                            $response = false;
                        }
                    break;
                    case 'Get Person':
                        $response = $this->getPerson();
                    break;
                }
                echo json_encode($response);
            }

        }

    }

    private function verifyData()
    {
        $data = new stdClass();
        $data->api = 'verify';
        $data->action = 'Person';
        $data->params = $this->getRequest()->params;
        $res = json_decode(ApiModel::doAPI($data));
        foreach($res as $key=>$value)
        {
            if(empty($value)){
                return false;
            }
        }

        return true;
        
    }

    private function createPerson()
    {

        $res = ApiModel::createPersonDetails($this->getPersonDetails());

        if($res->code !== '6000')
        {
            return $res;
        }

        $res = ApiModel::createPersonAddress($this->getPersonAddress());

        if($res->code !== '6000')
        {
            return $res;
        }

        $res = ApiModel::createPersonContact($this->getPersonContact());
        if($res->code !== '6000')
        {
            return $res;
        }

        return $res;
    }

    private function updatePerson()
    {
        $res = ApiModel::updatePersonDetails($this->getPersonDetails());
        error_log('response from updatePersonDetails: '.print_r($res, 1));
        if($res->code !== '6000')
        {
            return $res;
        }

        $res = ApiModel::updatePersonAddress($this->getPersonAddress());
        error_log('response from updatePersonAddress: '.print_r($res, 1));
        if($res->code !== '6000')
        {
            return $res;
        }

        $res = ApiModel::updatePersonContact($this->getPersonContact());
        error_log('response from updatePersonContact: '.print_r($res, 1));
        if($res->code !== '6000')
        {
            return $res;
        }

        return $res;
    }

    private function getPersonDetails(){

        $input = new stdClass();
        $input = $this->getRequest()->params;

        $data = new stdClass();
        $data->userId = $this->getParam('user', $input);
        $data->titleID = $this->getParam('titleID', $input);
        $data->firstName = $this->getParam('firstName', $input);
        $data->middleName = $this->getParam('middleName', $input);
        $data->lastName = $this->getParam('lastName', $input);
        $data->DOB = $this->getParam('DOB', $input);
        $data->nationality = (is_null($this->getParam('nationality', $input)))? 0 : $this->getParam('nationality', $input);
        $data->gender = (is_null($this->getParam('gender', $input)))? 0 : $this->getParam('gender', $input);
        return $data;
    }

    private function getPersonAddress(){

        $input = new stdClass();
        $input = $this->getRequest()->params;

        $data = new stdClass();
        $data->UserId = $this->getParam('user', $input);
        $data->AddressLine1 = $this->getParam('AddressLine1', $input);
        $data->AddressLine2 = $this->getParam('AddressLine2', $input);
        $data->AddressLine3 = $this->getParam('AddressLine3', $input);
        $data->AddressLine4 = $this->getParam('AddressLine4', $input);
        $data->postCode = $this->getParam('postCode', $input);
        $data->country = (is_null($this->getParam('country', $input)))? 0 : $this->getParam('country', $input);
        return $data;
    }

    private function getPersonContact()
    {
        $input = new stdClass();
        $input = $this->getRequest()->params;

        $data = new stdClass();
        $data->UserId = $this->getParam('user', $input);
        $data->email = $this->getParam('email', $input);
        $data->phoneMobile = $this->getParam('phoneMobile', $input);
        $data->phoneHome = $this->getParam('phoneHome', $input);
        return $data;
    }

    private function getPerson()
    {
        $input = new stdClass();
        $input = $this->getRequest()->person;
        $res = ApiModel::getPerson($input);
        return $res;
    }

    private function getParam($object, $input)
    {
        foreach($input as $key => $value){
            if($key == $object){
                return $value;
            }
        }
        return NULL;
    }

}