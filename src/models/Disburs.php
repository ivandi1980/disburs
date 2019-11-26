<?php

namespace Models;

use \Models\Database;

class Disburs
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * INSERT OR SAVE DATA INTO DATABASE
     * @return boolean
     */
    public function saveData($param): bool
    {

        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
        );

        $url = 'https://nextar.flip.id/disburse';

        $result = self::http_post($param, $url, $header);
        $result = json_decode($result);
        
        $id_disburs     = '';
        $status_disburs = '';
        $receipt         = '';
        $time_served     = '';

        if (isset($result->status) === true) {
            $id_disburs     = $result->id;
            $status_disburs = $result->status;
            $receipt         = $result->receipt;
            $time_served     = $result->time_served;
        }

        $request = json_encode($param);
        $response = json_encode($result);
        date_default_timezone_set('Asia/Jakarta');
        $datetime = date('Y-m-d H:i:s');

        // insert to database
        $this->db->query("INSERT INTO disburs_tbl (`api`,`id_disburs`,`time_served`,`status_disburs`,`receipt`,`request`,`response`,`created_at`,`updated_at`) VALUES (:api, :id_disburs, :time_served, :status_disburs, :receipt, :request, :response, :created_at, :updated_at)");
        $this->db->bind(':api', $url);
        $this->db->bind(':id_disburs', $id_disburs);
        $this->db->bind(':time_served', $time_served);
        $this->db->bind(':status_disburs', $status_disburs);
        $this->db->bind(':receipt', $receipt);
        $this->db->bind(':request', $request);
        $this->db->bind(':response', $response);
        $this->db->bind(':created_at', $datetime);
        $this->db->bind(':updated_at', $datetime);
        if ($this->db->execute())
            return true;
        return false;
    }

    /**
     * READ DATA FROM THE DATABASE
     * @return array
     */
    public function selectData(): array
    {
        $this->db->query("SELECT * FROM disburs_tbl");
        return $this->db->resultSet();
    }

    /**
     * UPDATE DATA INTO THE DATABASE
     */
    public function changeData($trx_id)
    {
        $header = array(
			"Content-Type: application/x-www-form-urlencoded",
		);

        $url = 'https://nextar.flip.id/disburse/'.$trx_id;
        
		$result = self::http_get($url,$header);
		$result = json_decode($result); 
			// update to database
			$status_disburs = '';
			$receipt 		= '';
			$time_served 	= ''; 
	
			if (isset($result->status) === true) {
				$status_disburs = $result->status;
				$receipt 		= $result->receipt;
				$time_served 	= $result->time_served;
			}
			$response = json_encode($result);
			date_default_timezone_set('Asia/Jakarta');
			$updated_at = date('Y-m-d H:i:s');

        $this->db->query("UPDATE disburs_tbl SET status_disburs = :status_disburs, receipt = :$receipt, time_served = :time_served, response = :response, updated_at = :updated_at WHERE id = :id");
        $this->db->bind(':status_disburs', $status_disburs);
        $this->db->bind(':receipt', $receipt);
        $this->db->bind(':time_served', $time_served);
        $this->db->bind(':response', $response);
        $this->db->bind(':updated_at', $updated_at);
        if ($this->db->execute())
            //return true;
            $r2 = $this->db->query("SELECT * FROM disburs_tbl WHERE id=".$trx_id);
            return $rs->fetch();
        return false;
    }

    /**
     * GET DATA FROM THE EXTERNAL API
     */
    public function getExternalApi($url,$header)
    {
		$ch = curl_init();
		
		$secret_key = "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41";
	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
    }


    /**
     * POST DATA TO THE EXTERNAL API
     */
    public function postExternalApi($param,$url,$header)
    {
		$ch = curl_init();

		$secret_key = "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");

		$response = curl_exec($ch);
		curl_close($ch);
        return $response;
    }
}
