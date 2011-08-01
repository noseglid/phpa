<?php

class DatabaseReporter extends Reporter {
  public function report() {
    Database::init($this->f);
    Database::createTables();
    Database::insertData($this->data);
  }

  public function describe() {
    return "SQLite3 Database";
  }
}


