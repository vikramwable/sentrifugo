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

/**
 * This gives employee report form.
 */
class Default_Form_Employeereport extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'formid');
        $this->setAttrib('name', 'frm_emp_report');

        $reporting_manager = new Zend_Form_Element_Text("reporting_manager");        
        $reporting_manager->setLabel("Reporting Manager");
        $reporting_manager->setAttrib('name', '');
        $reporting_manager->setAttrib('id', 'idreporting_manager');
        
        $joined_date = new Zend_Form_Element_Text("date_of_joining");        
        $joined_date->setLabel("Joined Date");
        $joined_date->setAttrib('readonly', 'readonly');
		
        $modeofentry = new Zend_Form_Element_Select("modeofentry");                
        $modeofentry->setLabel("Mode of Employment")
                            ->addMultiOptions(array('' => 'Select mode of employment',
                                                    'Direct' => 'Direct',
                                                    'Interview' => 'Interview',                                                    
                                                    'Reference' => 'Reference',
                                                    'Other' => 'Other',
                                                    ));        
        
        $jobtitle = new Zend_Form_Element_Select("jobtitle_id");
        $jobtitle->setLabel("Job Title");
        $jobtitle->setAttrib("onchange", "getpositions_req('department','business_unit','position_id','jobtitle_id');");        
        
        $position_id = new Zend_Form_Element_Select("position_id");
        $position_id->setLabel("Position");        
        $position_id->addMultiOptions(array(''=>'Select Position'));
        
        $emp_type = new Zend_Form_Element_Select("emp_status_id");
        $emp_type->setLabel("Employment Status");        
        $emp_type->addMultiOptions(array(''=>'Select Employment Status'));
        
        $emprole = new Zend_Form_Element_Select("emprole");
        $emprole->setLabel("Role");        
        $emprole->addMultiOptions(array(''=>'Select Role'));
        
        $department_id = new Zend_Form_Element_Multiselect("department_id");
        $department_id->setLabel("Department");        
        
        
        $businessunit_id = new Zend_Form_Element_Multiselect("businessunit_id");
        $businessunit_id->setLabel("Business Unit");        
        
        
        $submit = new Zend_Form_Element_Button('submit');        
        $submit->setAttrib('id', 'idsubmitbutton');
        $submit->setLabel('Report'); 
        
        $this->addElements(array($reporting_manager,$submit,$joined_date,$modeofentry,$jobtitle,$position_id,
                                 $emp_type,$emprole,$department_id,$businessunit_id));
        $this->setElementDecorators(array('ViewHelper')); 
    }
}