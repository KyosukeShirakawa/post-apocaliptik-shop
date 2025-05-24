<?php
interface IFileIO {
  function save($data); // save data to a file
  function load(); // load data from a file
}

abstract class FileIO implements IFileIO {
  protected $filepath;

  public function __construct($filename) { // constructor
    if (!is_readable($filename) || !is_writable($filename)) { // checks if the file is readable and writable
      throw new Exception("Data source {$filename} is invalid.");
    }
    $this->filepath = realpath($filename); // get the absolute path of the file
  }
}

class JsonIO extends FileIO { // provides JSON-specific file operations
  public function load($assoc = true) {
    $file_content = file_get_contents($this->filepath); // reads the file content
    return json_decode($file_content, $assoc) ?: []; // decodes the JSON content (returns an associative array $assoc = true)
  }

  public function save($data) {
    $json_content = json_encode($data, JSON_PRETTY_PRINT); // encodes the data to JSON format with pretty print.
    file_put_contents($this->filepath, $json_content); // saves the JSON content back to the file
  }
}

class SerializeIO extends FileIO {
  public function load() {
    $file_content = file_get_contents($this->filepath); // reads the file content
    return unserialize($file_content) ?: []; // unserializes the content (plain-text string -> format)
  }

  public function save($data) {
    $serialized_content = serialize($data); // serializes the data (format -> plain-text string)
    file_put_contents($this->filepath, $serialized_content); // saves the serialized content back to the file
  }
}

interface IStorage {
  function add($record): string;
  function findById(string $id);
  function findAll(array $params = []);
  function findOne(array $params = []);
  function update(string $id, $record);
  function delete(string $id);

  function findMany(callable $condition);
  function updateMany(callable $condition, callable $updater);
  function deleteMany(callable $condition);
}

class Storage implements IStorage {
  protected $contents; // for loaded data
  protected $io; // instance of a class implementing IFileIO

  public function __construct(IFileIO $io, $assoc = true) {
    $this->io = $io;
    $this->contents = (array)$this->io->load($assoc);
  }

  public function __destruct() {
    $this->io->save($this->contents); // saves contents back to the file when the object is destroyed
  }

  public function add($record): string {
    $id = uniqid(); // generates unique ID for the new record

    if (is_array($record)) { // adds the id to the record
      $record['id'] = $id;
    }
    else if (is_object($record)) {
      $record->id = $id;
    }

    $this->contents[$id] = $record; // stores the record in $contents using the generated ID as the key
    return $id;
  }

  public function findById(string $id) {
    return $this->contents[$id] ?? NULL; // gets a record by its ID from $contents
  }

  public function findAll(array $params = []) { // returns an array of matching items.
    return array_filter($this->contents, function ($item) use ($params) { // filters $contents based on the provided parameters
      foreach ($params as $key => $value) { 
        if (((array)$item)[$key] !== $value) { // checks if each item matches all key-value pairs in $params
          return FALSE; 
        }
      }
      return TRUE;
    });
  }

  public function findOne(array $params = []) {
    $found_items = $this->findAll($params); 
    $first_index = array_keys($found_items)[0] ?? NULL;
    return $found_items[$first_index] ?? NULL; // finds the first matching item from findAll()
  }

  public function update(string $id, $record) {
    $this->contents[$id] = $record; // updates the record with the given ID in $contents
  }

  public function delete(string $id) {
    unset($this->contents[$id]); // deletes the record with the given ID from $contents
  }

  public function findMany(callable $condition) {
    return array_filter($this->contents, $condition); // finds all records that satisfy the given condition callback
  }

  public function updateMany(callable $condition, callable $updater) {
    array_walk($this->contents, function (&$item) use ($condition, $updater) { // updates all records that match the condition using the updater callback
      if ($condition($item)) {
        $updater($item);
      }
    });
  }

  public function deleteMany(callable $condition) { // Deletes all records that match the condition.
    $this->contents = array_filter($this->contents, function ($item) use ($condition) {
      return !$condition($item);
    });
  }
}
