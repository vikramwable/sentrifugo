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

class Default_Model_Eeoccategory extends Zend_Db_Table_Abstract
{
    protected $_name = 'main_eeoccategory';
    protected $_primary = 'id';
	
	public function getEeoccategoryData($sort, $by, $pageNo, $perPage,$searchQuery)
	{
		$where = "isactive = 1";
		
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$Eeoccategorydata = $this->select()
    					   ->setIntegrityCheck(false)	    					
						   ->where($where)
    					   ->order("$by $sort") 
    					   ->limitPage($pageNo, $perPage);
		
		return $Eeoccategorydata;       		
	}
	public function getsingleEeoccategoryData($id)
	{
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$eeocCatData = $db->query("SELECT * FROM main_eeoccategory WHERE id = ".$id." AND isactive=1");
		$res = $eeocCatData->fetchAll();
		if (isset($res) && !empty($res)) 
		{	
			return $res;
		}
		else
			return 'norows';
	}
	
	public function SaveorUpdateEeocCategoryData($data, $where)
	{
	    if($where != ''){
			$this->update($data, $where);
			return 'update';
		} else {
			$this->insert($data);
			$id=$this->getAdapter()->lastInsertId('main_employmentstatus');
			return $id;
		}
		
	
	}
	public function getGrid($sort,$by,$perPage,$pageNo,$searchData,$call,$dashboardcall,$exParam1='',$exParam2='',$exParam3='',$exParam4='')
	{		
        $searchQuery = '';$tablecontent = '';  $searchArray = array();$data = array();$id='';
        $dataTmp = array();
		if($searchData != '' && $searchData!='undefined')
		{
			$searchValues = json_decode($searchData);
			foreach($searchValues as $key => $val)
			{
				$searchQuery .= " ".$key." like '%".$val."%' AND ";
				$searchArray[$key] = $val;
			}
			$searchQuery = rtrim($searchQuery," AND");					
		}

		/** search from grid - END **/
		$objName = 'eeoccategory';
		
		$tableFields = array('action'=>'Action','eeoccategory' => 'EEOC Category','description' => 'Description');
		
		$tablecontent = $this->getEeoccategoryData($sort, $by,$pageNo,$perPage,$searchQuery);     
		$dataTmp = array(
			'sort' => $sort,
			'by' => $by,
			'pageNo' => $pageNo,
			'perPage' => $perPage,				
			'tablecontent' => $tablecontent,
			'objectname' => $objName,
			'extra' => array(),
			'tableheader' => $tableFields,
			'jsGridFnName' => 'getAjaxgridData',
			'jsFillFnName' => '',
			'searchArray' => $searchArray,
			'call'=>$call,'dashboardcall'=>$dashboardcall
		);		
			
		return $dataTmp;
	}
}