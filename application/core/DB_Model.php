<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DB_Model extends CI_Model {
  protected $table;
  protected $database;

  public function __construct($database, $table, $id_col = "id") {
    parent::__construct();

    $this->database = $database;
    $this->table = $table;
    $this->id_column = $id_col;

    $this->load->database($database);

    $this->load->helper("url");
  }

  public function get($id) {
    $record = $this->db->where($this->id_column, $id)->get($this->table)->row();
    log_message("debug", "[DB_Model] Récupération de $id dans la table $this->table: " . json_encode($record)); // Ici on a bien une valeur et c'est bien cette fonction qui est appelée car on le log

    return $record;
  }

  public function getAll() {
    return $this->db->get($this->table)->result();
  }

  public function create($data) { // $data peut $être une table associative ou un objet
    $data = (array)$data;

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

    if ( count($this->file_columns) > 0 ) {
      $record = $this->get($id);
      log_message('debug', "[DB_Model] Produit récupéré dans la base de données: " . json_encode($record));
      log_message('debug', '[DB_Model] Voici l\'instance actuelle du modèle: ' . json_encode($this));

      foreach ( $this->file_columns as $file_column ) {
        if ( isset($data[$file_column]) ) {
          //On commence par gérer l'upload de l'image
          $uploaddir = "";

          $file = $data[$file_column];
          $filename = $file["name"];
          $imageName = "";

          if ( $this->table == "products" ) {
            $this->load->model("providers_model");
            $provider = $this->providers_model->get($record->provider_id);

            log_message('debug', "[DB_Model] Fournisseur du produit:" . json_encode($provider));

            $imageName = "taylorbox - " . $provider->label . " " . $record->label;
            log_message('debug', "[DB_Model] Nom d'image avant génération: " . $imageName);
            $imageName = $this->craftImageName($imageName, $filename);
            log_message('debug', '[DB_Model] Mise à jour du champs ' . $file_column . ' dans la table ' . $this->table . ' pour l\'enregistrement ' . $id);
            log_message('debug', "[DB_Model] Nom d'image généré: " . $imageName);
          } else {
            $imageName = $this->craftImageName($record->label, $filename, ((isset($record->index) ? $record->index + 1 : 0)));
          }


          $uploaddir = "/var/www/static/taylorbox/images/" . $this->table . "/" . $file_column . "/large_700_1000/";

          if( !is_dir($uploaddir) )
            mkdir($uploaddir);

          $filepath = $uploaddir . $imageName;
          move_uploaded_file($file["tmp_name"], $filepath);

          //Maintenant on s'occupe de gérer l'insertion dans la bdd
          $data[$file_column] = $imageName;
        }
      }
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
        $sizes = ["large_700_1000", "medium_570_760", "small_270_360"];

        foreach ( $sizes as $size ) {
          $filepath = "/var/www/static/taylorbox/images/" . $this->table . "/" . $file_column . "/" . $size . "/" . $filename;

          if ( file_exists($filepath) )
            unlink($filepath);
        }
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

  protected function craftImageName($label, $filename = "undefined.jpg", $index = 0) {
    $file_data = pathinfo($filename);
    log_message('error', "Informations sur le fichier: " . json_encode($file_data));

    $name = strtolower(url_title(strip_accents($label)) . "-" . $index);

    if ( isset($file_data["extension"]) )
      $name .= "." . $file_data["extension"];
    else
      $name .= ".jpg";

    return strtolower($name);
  }

}
