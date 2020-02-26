<?php
/********************************************************************************* 
 *  This file is part of Sentrifugo.
 *  Copyright (C) 2014 Webshar
 *   
 *  Sentrifugo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Sentrifugo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Sentrifugo.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Sentrifugo Support <support@webshar.org>
 ********************************************************************************/

class Default_Form_wizardpreference extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id', 'formid');
		$this->setAttrib('name', 'systempreference');


        $id = new Zend_Form_Element_Hidden('id');
        $currencyid = new Zend_Form_Element_Hidden('currencyid');
        $organisationid = new Zend_Form_Element_Hidden('organisationid');
        $empcodeid = new Zend_Form_Element_Hidden('empcodeid');
        
		$dateformatid = new Zend_Form_Element_Select('dateformatid');
        $dateformatid->setAttrib('class', 'selectoption');
        $dateformatid->setRegisterInArrayValidator(false);
        $dateformatid->setRequired(true);
		$dateformatid->addValidator('NotEmpty', false, array('messages' => 'Please select date format.'));
		
		$timeformatid = new Zend_Form_Element_Select('timeformatid');
        $timeformatid->setAttrib('class', 'selectoption');
        $timeformatid->setRegisterInArrayValidator(false);
        $timeformatid->setRequired(true);
        $timeformatid->addValidator('NotEmpty', false, array('messages' => 'Please select time format.')); 

        $timezoneid = new Zend_Form_Element_Select('timezoneid');
        $timezoneid->setAttrib('class', 'selectoption');
        $timezoneid->setRegisterInArrayValidator(false);
        $timezoneid->setRequired(true);
        $timezoneid->addValidator('NotEmpty', false, array('messages' => 'Please select time zone preference.'));	
		$timezoneid->addMultiOption('','Select Time zone');
		$timezoneModal = new Default_Model_Timezone();
	    	$timezoneData = $timezoneModal->fetchAll('isactive=1','timezone')->toArray();;
			foreach ($timezoneData as $data){
		$timezoneid->addMultiOption($data['id'],$data['timezone'].' ['.$data['timezone_abbr'].']');
	    	}

        $currencyname = new Zend_Form_Element_Text('currencyname');
        $currencyname->setAttrib('maxLength', 50);
        
        $currencyname->addFilter(new Zend_Filter_StringTrim());
        $currencyname->setRequired(true);
        $currencyname->addValidator('NotEmpty', false, array('messages' => 'Please enter currency.'));  
        
		$currencyname->addValidator("regex",true,array(
                           'pattern'=>'/^[a-zA-Z][a-zA-Z0-9\s]*$/', 
        
                           'messages'=>array(
                               'regexNotMatch'=>'Please enter valid currency.'
                           )
        	));	
		$currencyname->addValidator(new Zend_Validate_Db_NoRecordExists(
                                              array('table'=>'main_currency',
                                                        'field'=>'currencyname',
                                                      'exclude'=>'id!="'.Zend_Controller_Front::getInstance()->getRequest()->getParam('currencyid').'" and isactive=1',    
                                                 ) )  
                                    );
        $currencyname->getValidator('Db_NoRecordExists')->setMessage('Currency already exists.');
		
		$currencycode = new Zend_Form_Element_Text('currencycode');
        $currencycode->setAttrib('maxLength', 20);
        
        $currencycode->addFilter(new Zend_Filter_StringTrim());
        $currencycode->setRequired(true);
        $currencycode->addValidator('NotEmpty', false, array('messages' => 'Please enter currency code.'));  
        
        $currencycode->addValidator("regex",true,array(
                           'pattern'=>'/^[a-zA-Z][a-zA-Z0-9]*$/', 
                          
                           'messages'=>array(
                               'regexNotMatch'=>'Please enter valid currency code.'
                           )
        	));				
		
		$currencycode->addValidator(new Zend_Validate_Db_NoRecordExists(
                                              array('table'=>'main_currency',
                                                        'field'=>'currencycode',
                                                      'exclude'=>'id!="'.Zend_Controller_Front::getInstance()->getRequest()->getParam('currencyid').'" and isactive=1',    
                                                 ) )  
                                    );
        $currencycode->getValidator('Db_NoRecordExists')->setMessage('Currency code already exists.');	

        $passwordid = new Zend_Form_Element_Select('passwordid');
        $passwordid->setAttrib('class', 'selectoption');
		$passwordid->setAttrib('onchange', 'displayPasswordDesc(this)');
        $passwordid->setRegisterInArrayValidator(false);
        $passwordid->setRequired(true);
		$passwordid->addValidator('NotEmpty', false, array('messages' => 'Please select default password.'));  		
		
		$perm_country = new Zend_Form_Element_Select('perm_country');
		$perm_country->setAttrib('onchange', 'displayParticularState(this,"","perm_state","")');
        $perm_country->setRegisterInArrayValidator(false);
        	$countriesModel = new Default_Model_Countries();
        	$countrieslistArr = $countriesModel->getTotalCountriesList('addcountry');
			if(sizeof($countrieslistArr)>0)
            {
                $perm_country->addMultiOption('','Select Country');
                foreach ($countrieslistArr as $countrieslistres)
                {
                     $perm_country->addMultiOption($countrieslistres['id'],  utf8_encode($countrieslistres['country_name']));
                }
           }
        $perm_country->setRequired(true);
		$perm_country->addValidator('NotEmpty', false, array('messages' => 'Please select country.'));   
        
        $perm_state = new Zend_Form_Element_Select('perm_state');
		$perm_state->setAttrib('onchange', 'displayParticularCity(this,"","perm_city","")');
        $perm_state->setRegisterInArrayValidator(false);
        $perm_state->addMultiOption('','Select State');
        $perm_state->setRequired(true);
		$perm_state->addValidator('NotEmpty', false, array('messages' => 'Please select state.')); 
		
        $perm_city = new Zend_Form_Element_Select('perm_city');
        $perm_city->setRegisterInArrayValidator(false);
        $perm_city->addMultiOption('','Select City');
        $perm_city->setRequired(true);
		$perm_city->addValidator('NotEmpty', false, array('messages' => 'Please select city.'));

		$workcodename = new Zend_Form_Element_Multiselect('workcodename');
        $workcodename->setRegisterInArrayValidator(false);
        $workcodename->setRequired(true);
		$workcodename->addValidator('NotEmpty', false, array('messages' => 'Please select employment status.'));
		
        
        $empCode = new Zend_Form_Element_Text('employee_code');
        $empCode->addFilter(new Zend_Filter_StringTrim());
		$empCode->setAttrib('maxLength', 5);
		$empCode->setRequired(true);
        $empCode->addValidator('NotEmpty', false, array('messages' => 'Please enter employee code.')); 
		$empCode->addValidators(array(array('StringLength',false,
								array('min' => 1,
									  'max' => 5,
									  'messages' => array(
									   Zend_Validate_StringLength::TOO_LONG =>
									  'Employee code must contain at most %max% characters.',
									  Zend_Validate_StringLength::TOO_SHORT =>
									  'Employee code must contain at least %min% characters.')))));
		$empCode->addValidators(array(
			         array(
			             'validator'   => 'Regex',
			             'breakChainOnFailure' => true,
			             'options'     => array( 
			             //'pattern' => '/^[A-Za-z]+$/',
			         	 'pattern'=> '/^[A-Za-z][a-zA-Z@\-]*$/',	
			                 'messages' => array(
			                     Zend_Validate_Regex::NOT_MATCH =>'Please enter valid employee code.'
			                 )
			             )
			         )
			     ));
   	

        $submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setLabel('Save');

		 $this->addElements(array($timezoneid,$id,$currencyid,$organisationid,$empcodeid,$dateformatid,$timeformatid,$currencycode,$currencyname,$passwordid,$perm_country,$perm_state,$perm_city,$workcodename,$empCode,$submit));
         $this->setElementDecorators(array('ViewHelper')); 
	}
}