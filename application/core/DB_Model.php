<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DB_Model extends CI_Model {
  protected $table;
  protected $database;

  public function __construct($database, $table, $id_col = "id", $file_columns = []) {
    parent::__construct();

    $this->database = $database;
    $this->table = $table;
    $this->id_column = $id_col;
    $this->file_columns = $file_columns;

    $this->load->database($database);

    $this->load->helper("url");
    $this->load->config('uploads', true);
  }

  public function get($id) {
    $record = $this->db->where($this->id_column, $id)->get($this->table)->row();
    log_message("debug", "[DB_Model] Récupération de $id dans la table $this->table: " . json_encode($record)); // Ici on a bien une valeur et c'est bien cette fonction qui est appelée car on le log

    return $record;
  }

  public function getAll() {
    return $this->db->get($this->table)->result();
  }

  public function create($data) { // $data peut être une table associative ou un objet
    $data = (array)$data;
    $data = $this->store_files($data);

    if ( $this->db->insert($this->table, $data) ) {
      return $this->get($this->db->insert_id());
    } else {
      log_message('error', 'Error when inserting record in table ' . strtolower($this->table) . '\nRecord infos:\n\t' . implode("\n\t", $data));
      return false;
    }
  }

  public function update($id, $data) { // $data peut $être une table associative ou un objet
    log_message('debug', '[DB_Model] Demande de mise à jour du produit ' . $id);

    $data = (array)$data;
    $data = $this->store_files($data);

    if ( count($this->file_columns) > 0 ) {
      $record = $this->get($id);
    }

    if ( $this->db->where($this->id_column, $id)->update($this->table, $data) ) {
      return $id;
    } else {
      log_message('error', 'Error when updating record in table ' . strtolower($this->table) . '\nRecord infos:\n\t' . implode("\n\t", $data));
      return false;
    }
  }

  public function delete($id) {
    if ( count($this->file_columns) > 0 ) {
      $record = $this->get($id);

      foreach ( $this->file_columns as $file_column ) {
        $filename = $record->$file_column;
        $filepath = $this->config->config["upload_path"] . $filename;

          if ( file_exists($filepath) )
            unlink($filepath);
      }
    }

    if ($this->db->where($this->id_column, $id)->delete($this->table)) {
      return true;
    } else {
      log_message('error', 'Error when deleting record ' . $id . ' in table ' . strtolower($this->table));
      return false;
    }
  }

  public function select_distinct($column_name) {
    return $this->db->distinct()->select($column_name)->get($this->table)->result();
  }

  public function exists($id) {
    $rec = $this->db->where($this->id_column, $id)->get($this->table)->result();
    if ( $rec )
      return true;
    else
      return false;
  }

  public function search($searchArray) {
    return $this->db->where($searchArray)->get($this->table)->result();
  }

  protected function store_files($data) {
    $this->load->library('upload', $this->config->config['uploads']);

    foreach ( $this->file_columns as $fc ) {
      if (!empty($_FILES[$fc]['name'])) {
        $challenge[$fc] = $_FILES[$fc];

        if ( $this->upload->do_upload($fc) ) {
          $upload_result = $this->upload->data();
          $data[$fc] = $upload_result['file_name'];
        } else {
          $error = array('error' => $this->upload->display_errors());

          throw new Exception(print_r($error, true), 1);
        }
      }
    }

    return $data;
  }

}
