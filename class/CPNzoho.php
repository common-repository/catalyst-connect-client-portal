<?php

/*
Zoho API version 2
Developed By : Mahidul Islam Tamim
*/

class CPNzoho {

	private $authtoken;
	private $newFormat;
	private $xml;
	private $error;
	private $code;
	private $msg;

	public function getError()
	{
		return $this->error;
	}

	public function flushError()
	{
		$this->error = '';
	}

	public function getCode()
	{
		return $this->code;
	}

	public function flushCode()
	{
		$this->code = '';
	}

	public function getMsg()
	{
		return $this->msg;
	}

	public function flushMsg()
	{
		$this->msg = '';
	}

	public function __construct($authtoken = '', $newFormat = 1)
	{
	}

	public function setAuth()
	{

        global $wpdb;

		$auth_data       = $wpdb->get_row( "SELECT * FROM ccgclientportal_auth WHERE apifor = 'crm' ORDER BY id DESC" );	
		$this->newFormat = 1;

		if(isset($auth_data->create_time)){
			$db_time_with_increase_time = date('Y-m-d H:i:s', strtotime("+59 minutes", strtotime($auth_data->create_time)));
			$dtA = new DateTime($db_time_with_increase_time);
			$dtB = new DateTime();
			
			if ( $dtA < $dtB ) {
				
				$insArr['refresh_token'] = $data['refresh_token'] = $auth_data->refresh_token;

			  	$token_data = $this->refresh_authtoken($data);
				$token_dataArr = json_decode($token_data);

				if(isset($token_dataArr->access_token)){ 
					$this->authtoken = 'Zoho-oauthtoken '.$token_dataArr->access_token; 				

					$insArr['access_token'] = $token_dataArr->access_token;
					$insArr['orgid'] = $auth_data->orgid;
					$insArr['apifor'] = "crm";
					$insArr['create_time'] = date('Y-m-d H:i:s');

					$wpdb->insert('ccgclientportal_auth', $insArr);
				}
			}else{
				$this->authtoken = 'Zoho-oauthtoken '.$auth_data->access_token;
			}

		}
		
		
	}


	function refresh_authtoken($data){

		$client_id = "1000.824P2EC4D83CZ1NF0DWVZBR48H2UOH";
		$client_secret = "1b86df64afad732e80e8f31c44854bdaf658df37af";

		$fields = array(
			'refresh_token' => $data['refresh_token'], 
			'client_id' => $client_id, 
			'client_secret' => $client_secret, 
			'grant_type' => "refresh_token"
		);
		$zoho_url = "https://accounts.zoho.com/oauth/v2/token";

		$args = array(
		    'body' => $fields,
		    'timeout' => '5',
		    'redirection' => '5',
		    'httpversion' => '1.0',
		    'blocking' => true,
		    'headers' => array(
		    	// 'Authorization' => $authtoken
		    ),
		    'cookies' => array()
		);

		$request = wp_remote_post( $zoho_url, $args );
								
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );

		return  $body ;

	}


	/*
	* Description: Gets records from a zoho module
	* Parameters:  $module= The name of the Zoho Module
	*              $maxRecords= The maximum number of records to be returned
	*              $recordsPerPage= The number of records in single API call to be fetched. Default is 200
	* Returns: An array(count=>NUMBER_OF_RECORDS, data=array(THE ACTUAL DATA WITH ALL FIELDS AND ITS VALUE AS KEY => VALUE PAIR))
	* Notes: It would be nice to check if getError() method returns any value after calling this function for error handling
	*/
	public function getRecords($module, $selectColumns = array() , $maxRecords = 400, $sortColumn = 'Modified_Time', $sortBy = 'asc')
	{
		try
		{
			$post = array();
			$hasMore = true;
			$records = array();

			$url = "https://www.zohoapis.com/crm/v2/$module";
			
			if (count($selectColumns)) $selectColumns = implode(',', $selectColumns);
			$post['fields'] = $selectColumns;
			$post['sort_order'] = 'desc';
			$post['page'] = 1;
			$post['per_page'] = 200;
			
			$resultAr = array();
			
			while ($hasMore)
			{
				$fields_string = http_build_query($post, '', "&");
				$zoho_url = $url . "?$fields_string";

				$result = $this->get_from_zoho($zoho_url);
				if(isset($result->data)) $resultAr = array_merge($resultAr, $result->data);
				
				$post['page']= $post['page']+1;
				if (!isset($result->data) or count($result->data) < 200 or empty($result->data) or count($resultAr) >= $maxRecords) $hasMore = false;
				
			}

			$output = json_encode($resultAr, true);
			$records = json_decode($output, true);
			
			return array(
				'count' => count($records) ,
				'data' => $records
			);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}
	}

	public function getCustom_views($module)
	{
		try
		{

			$post = array();
			$post['module'] = $module;

			$url = "https://www.zohoapis.com/crm/v2/settings/custom_views";

			$fields_string = http_build_query($post, '', "&");
			$zoho_url = $url . "?$fields_string";

			$result = $this->get_from_zoho($zoho_url);
			
			return json_encode($result);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}

		return false;
	}

	/*
	* Description: Get record details of any module by its zoho record id
	* Parameters:  $id= A valid Zoho_record_id 
	*              $module= The name of the Zoho Module for which record id is provided
	* Returns: An array(count=>NUMBER_OF_RECORDS, data=array(THE ACTUAL DATA WITH ALL FIELDS AND ITS VALUE AS KEY => VALUE PAIR))
	* Notes: It would be nice to check if getError() method returns any value after calling this function for error handling
	*/
	public function getRecordsById($id, $module)
	{
		if (!$id) return false;
		try
		{
			$records = array();
			$resultAr = array();
			$zoho_url = "https://www.zohoapis.com/crm/v2/$module/$id";
			$result = $this->get_from_zoho($zoho_url);
			
			if(isset($result->data)) $resultAr = $result->data;
			$output = json_encode($resultAr, true);
			$records = json_decode($output, true);
			return json_encode( array(
					'count' => count($records) ,
					'data' => $records
				)
			);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}
	}

	public function getSearchRecordsByPDC($module, $searchColumn, $searchValue, $selectColumns = array() , $maxRecords = 400)
	{
		try
		{
			$post = array();
			if (count($selectColumns)) $selectColumns = implode(',', $selectColumns);
			$post['fields'] = $selectColumns;
			$post[$searchColumn] = $searchValue;
			$post['page'] = 1;
			$post['per_page'] = 200;
			$url = "https://www.zohoapis.com/crm/v2/$module/search";

			$hasMore = true;
			$records = array();
			$resultAr = array();
			while ($hasMore)
			{
				$fields_string = http_build_query($post, '', "&");
				$zoho_url = $url . "?$fields_string";
				
				$result = $this->get_from_zoho($zoho_url);
				if(isset($result->data)) $resultAr = array_merge($resultAr, $result->data);

				$post['page']= $post['page']+1;
				if (!isset($result->data) or count($result->data) < 200 or empty($result->data) or count($resultAr) >= $maxRecords) $hasMore = false;
			}

			$output = json_encode($resultAr, true);
			$records = json_decode($output, true);

			return json_encode( 
				array(
					'count' => count($records) ,
					'data' => $records
				)
			);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage(); 
		}
	}

	/*
	*Description: Gets related records from zoho module.
	*Parameters:  $module= The name of the Zoho Module for which record list want
	*			  $id= A valid Zoho_record_id 
	*             $parentModule= The name of the Zoho Module for which record id is provided
	*/
	public function getRelatedRecords($module, $id, $parentModule, $maxRecords = 400)
	{
		if (!$id) return false;
		try
		{
			$post = array();
			$post['page']= $page = 1;
			$post['per_page'] = 200;
			$zoho_url = "https://www.zohoapis.com/crm/v2/$parentModule/$id/$module";

			$hasMore = true;
			$records = array();
			$resultAr = array();

			while ($hasMore)
			{
				$result = $this->get_from_zoho($zoho_url);
				if(isset($result->data)) $resultAr = array_merge($resultAr, $result->data);

				$post['page']= $post['page']+1;
				if (!isset($result->data) or count($result->data) < 200 or empty($result->data) or count($resultAr) >= $maxRecords) $hasMore = false;
			}

			$output = json_encode($resultAr, true);
			$records = json_decode($output, true);
			
			return json_encode( array(
					'count' => count($records) ,
					'data' => $records
				)
			);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}
	}

	
	public function getSearchRecords($module, $searchCondition, $selectColumns = array(), $maxRecords = 400)
	{
		try
		{
			$post = array();
			if (count($selectColumns)) $selectColumns = implode(',', $selectColumns);
			$post['fields'] = $selectColumns;
			$post['criteria'] = $searchCondition;
			$post['page'] = 1;
			$post['per_page'] = 200;
			$url = "https://www.zohoapis.com/crm/v2/$module/search";
			$hasMore = true;
			$records = array();
			$resultAr = array();
			while ($hasMore)
			{
				$fields_string = http_build_query($post, '', "&");
				$zoho_url      = $url . "?$fields_string";

				$result = $this->get_from_zoho($zoho_url);

				if(isset($result->data)) $resultAr = array_merge($resultAr, $result->data);
				
				$post['page']= $post['page']+1;
				if (!isset($result->data) or count($result->data) < 200 or empty($result->data) or count($resultAr) >= $maxRecords) $hasMore = false;
			}
			$output = json_encode($resultAr, true);
			$records = json_decode($output, true);
			return json_encode( array(
					'count' => count($records) ,
					'data' => $records
				)
			);
			
		}

		catch(Exception $e)
		{
			$this->error = $e->getmessage();
		}
	}



	/*
	* Description: Used for inserting the records
	*/
	public function insertRecords($data, $module)
	{
		try
		{
			$jsonpost = json_encode($data);
			$post = '{"data": '.$jsonpost.',"trigger": ["workflow"]}';
			$zoho_url = "https://www.zohoapis.com/crm/v2/$module";
			$result = $this->post_to_zoho($zoho_url, $post);
			
			return $result;
		}
		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}
	}


	public function updateRecords($id,$data, $module)
	{
		try
		{
			$post = array();
			$data[0]['id'] = $id;
			
			$jsonpost = json_encode($data);
			$post = '{"data": '.$jsonpost.',"trigger": ["workflow"]}';
			$zoho_url = "https://www.zohoapis.com/crm/v2/$module";

			$result = $this->update_to_zoho($zoho_url, $post);
			
			return $result;
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}
	}



	public function deleteRecordsN($id , $module)
	{
		try
		{
			$post = array();
			$post['ids'] = $id;
			$url = "https://www.zohoapis.com/crm/v2/$module";

			$fields_string = http_build_query($post, '', "&");
			$zoho_url = $url . "?$fields_string";


			$result = $this->delete_from_zoho($zoho_url);
			return json_decode($result);
			
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}
	}

	// Get Fields From ZOHO CRM
	public function getFields($module, $type = '')
	{
		try
		{
			$post = array();
			$records = array();
			$post['module'] = $module;
			
			if($type != ''){
				$post['type'] = "$type";
			}
			
			$url = "https://www.zohoapis.com/crm/v2/settings/fields";

			$fields_string = http_build_query($post, '', "&");
			$zoho_url = $url . "?$fields_string";

			$result = $this->get_from_zoho($zoho_url);
			
			// return $result;
			return json_encode($result);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}

		return false;
	}

	public function getRelatedMLists($module)
	{
		try
		{			
			$url = "https://www.zohoapis.com/crm/v2/settings/related_lists?module=".$module;

			$result = $this->get_from_zoho($url);
			
			return $result;
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}

		return false;
	}

	public function getModule()
	{

		try
		{
			$post = array();
			$output = '';
			$records = array();
			$zoho_url = "https://www.zohoapis.com/crm/v2/settings/modules";

			$result = $this->get_from_zoho($zoho_url);
			
			return json_encode($result);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}

		return false;
	}

	
	function makeJson($data){
		$str = '';
		foreach ($data as $key => $value) {
			$post = json_encode($value, JSON_FORCE_OBJECT);
    		$str .= $post.',';
		}
		$strtrim = rtrim($str,',');
		$post = '{"data": ['.$strtrim.'],"trigger": ["workflow"]}';

		return $post;
	}
	

	function post_to_zoho($zoho_url, $fields){
		$authtoken = $this->authtoken;
							
		$args = array(
		    'body' => $fields,
		    'timeout' => '5',
		    'redirection' => '5',
		    'httpversion' => '1.0',
		    'blocking' => true,
		    'headers' => array(
		    	'Authorization' => $authtoken
		    ),
		    'cookies' => array()
		);

		$request = wp_remote_post( $zoho_url, $args );
								
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );

		return  $body ;
	}

	function post_to_zoho_free($zoho_url, $fields){
							
		$args = array(
		    'body' => json_encode($fields),
		    'timeout' => '5',
		    'redirection' => '5',
		    'httpversion' => '1.0',
		    'blocking' => true,
		    'cookies' => array()
		);

		$request = wp_remote_post( $zoho_url, $args );

				//echo "<pre>";print_r($request);exit();
								
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );



		return  $body ;
	}

	function update_to_zoho($zoho_url, $fields){
		$authtoken = $this->authtoken;
		
		$fields = json_decode($fields);			
		
		$args = array(
		    'body' => json_encode($fields),
		    'timeout' => '5',
		    'redirection' => '5',
		    'httpversion' => '1.0',
		    'blocking' => true,
		    'method' => 'PUT',
		    'headers' => array(
		    	'Authorization' => $authtoken
		    ),
		    'cookies' => array()
		);
		$request = wp_remote_request( $zoho_url, $args );
																									
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		
		return  $body ;

	}

	private function get_from_zoho($url){
		
		$authtoken = $this->authtoken;
		if($authtoken == "") return false;

		$args = array(
		    'headers' => array(
		        'Authorization' => $authtoken
		    )
		);

		$request = wp_remote_get( $url, $args );
					
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );

		return json_decode( $body );
		

	}

	public function savePrfilePhoto($module, $id)
	{
		try{
			$zoho_url = "https://www.zohoapis.com/crm/v2/$module/$id/photo";
			$result = $this->download_from_zoho($zoho_url);
			return $result;
		}

		catch(Exception $e)
		{
			$this->error = $e->getmessage();
		}
	}

	public function getUserByIdN($id)
	{
		try
		{
			$post = array();
			$output = '';
			$records = array();
			$zoho_url = "https://www.zohoapis.com/crm/v2/users/".$id;

			$result = $this->get_from_zoho($zoho_url);
			
			return json_encode($result);
		}

		catch(Exception $e)
		{
			$this->error = $e->getMessage();
		}

		return false;
	}



	/*Download file
	*Description: Used for downloading file
	*/
	public function downloadFile($module, $id, $attachment_id)
	{
		try
		{
			$post['scope'] = 'ZohoCRM.modules.'.$module.'.all';
			$url           = "https://www.zohoapis.com/crm/v2/$module/$id/Attachments/$attachment_id";

			$fields_string = http_build_query($post, '', "&");
			$zoho_url      = $url . "?$fields_string";

			$result = $this->download_from_zoho($zoho_url);
			
			return $result;
		}

		catch(Exception $e)
		{
			$this->error = $e->getmessage();
		}
	}
	
	
	public function getFiles($module, $id)
	{
		try
		{
			$post['scope'] = 'ZohoCRM.modules.'.$module.'.all';

			$url = "https://www.zohoapis.com/crm/v2/$module/$id/Attachments";

			$fields_string = http_build_query($post, '', "&");
			$zoho_url = $url . "?$fields_string";

			$result = $this->get_from_zoho($zoho_url);
			return json_encode($result);
		}

		catch(Exception $e)
		{
			$this->error = $e->getmessage();
		}
	}


	public function deleteFile($module, $id, $attachment_id)
	{
		try
		{
			$zoho_url = "https://www.zohoapis.com/crm/v2/$module/$id/Attachments/$attachment_id";

			$result = $this->delete_from_zoho($zoho_url);
			return $result;
		}

		catch(Exception $e)
		{
			$this->error = $e->getmessage();
		}
	}

	

	function download_from_zoho($zoho_url){

		$authtoken = $this->authtoken;
		
		$args = array(
		    'headers' => array(
		        'Authorization' => $authtoken
		    )
		);

		$request = wp_remote_get( $zoho_url, $args );

		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );

		return  $body ;

	}

	
	private function delete_from_zoho($url){
					
		$authtoken = $this->authtoken;
				

		$args = array(
		    'method' => 'DELETE',
		    'headers' => array(
		    	'Authorization' => $authtoken
		    )
		);
		$request = wp_remote_request( $url, $args );
																												
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		
		return  $body ;


	}

		
}
?>