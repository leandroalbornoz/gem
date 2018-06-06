<?php

class Video_model extends MY_Model {

	public function __construct() {
		parent::__construct();
		$this->table_name = 'feria_video';
		$this->msg_name = 'Video';
		$this->id_name = 'id';
		$this->columnas = array('id', 'path', 'thumbnail', 'pie', 'posicion', 'feria_id', 'fecha_alta', 'fecha_baja');
		$this->fields = array(
			'path' => array('label' => 'URL (sólo Youtube o Vimeo)', 'maxlength' => '255', 'required' => TRUE),
			'pie' => array('label' => 'Texto', 'maxlength' => '255', 'required' => TRUE),
		);
		$this->requeridos = array('path', 'pie', 'feria_id');
	}

	public function get_posicion_max($feria_id = "") {
		$this->db->select_max('posicion');
		$this->db->from('feria_video');
		$this->db->where('feria_id', $feria_id);

		$query = $this->db->get();
		$data = $query->row_array();

		if (isset($data['posicion'])) {
			$posicion = $data['posicion'] + 1;
		} else {
			$posicion = 1;
		}
		return $posicion;
	}

	public function get_json_video($servidor = "", $video_id = "") {
		switch ($servidor):
			case 'youtube':
				//$url = 'http://gdata.youtube.com/feeds/api/videos/'.$video_id.'?v=2&alt=jsonc';
				$url = 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . '&part=snippet&key=AIzaSyD7jN3GEVUyNEoAD-kta0dB280D4rKiYSQ';
				break;
			case 'vimeo':
				$url = 'http://vimeo.com/api/v2/video/' . $video_id . '.json';
				break;
		endswitch;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
		curl_setopt($ch, CURLOPT_PROXY, 'proxy.hacienda.mendoza.gov.ar:8080');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		curl_exec($ch);
		$curl_scraped_page = curl_exec($ch);
		curl_close($ch);
		$json = json_decode($curl_scraped_page);

		return $json;
	}
}