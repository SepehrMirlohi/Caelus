<?php


class DB
{

    public ADOConnection $connection;
    public string $tableName;

    public function __construct($table)
    {
        $this->tableName = $table;
        if (DB_TABLE_REFRESH){
            $this->refresh_tables();
        }
        if (!file_exists("../app/database.json")) {
            $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
            $connection->query("CREATE DATABASE IF NOT EXISTS ". DB_NAME);
            $payload = [

            ];
            file_put_contents("../app/database.json", json_encode($payload));
            $this->connection = NewADOConnection("mysqli");
            $this->connection->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $this->create_tables();
        }
        // Creating tables ------------------
        if (DB_TABLE_REFRESH){
            die;
        }
        $this->connection = NewADOConnection("mysqli");
        $this->connection->connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);


    }

    private function create_tables(): void
    {
         foreach (glob("../app/models/*.xml") as $file)
         {
             $schema = new adoSchema($this->connection);
             $result = $schema->parseSchema($file);
             $schema->executeSchema();

         }
    }

    private function refresh_tables(): void
    {

        $tables_fields = [];
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $result = $connection->query("SHOW TABLES");
        if ($result->num_rows != 0){
            $tables = $connection->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA LIKE 'sinaweb'");

            foreach($tables->fetch_all(1) as $table){
                $tables_sec = $connection->query("SHOW COLUMNS FROM ". $table['TABLE_NAME']);
                $tables_fields[$table['TABLE_NAME']] = $tables_sec->fetch_all(1);
            }
        }
        $undefined_field = [];
        if (!empty($tables_fields)){
            foreach (glob("../app/models/*.xml") as $xml){


                $xmlstring = file_get_contents($xml);
                $xml = simplexml_load_string($xmlstring);
                $json = json_encode($xml);
                $xml_array = json_decode($json,TRUE);

                foreach ($xml_array['table']["field"] as $xml_field){
                    $status = 0;
                    if (!isset($tables_fields[strtolower($xml_array['table']['@attributes']['name'])])){
                        if (!isset($undefined_field[strtolower($xml_array['table']['@attributes']['name'])])){
                            echo "----------------------------------------------- <br>";
                            echo "<span style='color:red;'>" . $xml_array['table']['@attributes']['name'] . " NOT FOUND! at the end of process if feild are not compeleted the refresh process will automatically start!</span><br>";
                            echo "----------------------------------------------- <br>";
                            $undefined_field[strtolower($xml_array['table']['@attributes']['name'])] = [
                                "table_name" => strtolower($xml_array['table']['@attributes']['name'])
                            ];
                        }
                    }else{
                        if (sizeof($tables_fields[strtolower($xml_array['table']['@attributes']['name'])]) > sizeof($xml_array['table']["field"])){
                            if (!isset($undefined_field[strtolower($xml_array['table']['@attributes']['name'])])){
                                echo "----------------------------------------------- <br>";
                                echo "<span style='color:red;'>" . $xml_array['table']['@attributes']['name'] . " In database side has more fields!</span><br>";
                                echo "----------------------------------------------- <br>";
                                $undefined_field[strtolower($xml_array['table']['@attributes']['name'])] = [
                                    "table_name" => strtolower($xml_array['table']['@attributes']['name'])
                                ];
                            }
                        }else{
                            foreach ($tables_fields[strtolower($xml_array['table']['@attributes']['name'])] as $table){
                                if ($xml_field['@attributes']['name'] == $table['Field']){
                                    $status = 1;
                                    echo "<span style='color:red;'>".$table['Field']."</span> has been found!";
                                    echo "<br>";
                                    break;
                                }
                            }
                            if ($status == 0) {
                                echo "----------------------------------------------- <br>";
                                echo "<span style='color:red;'>" . $xml_field['@attributes']['name'] . " NOT FOUND! at the end of process if feild are not compeleted the refresh process will automatically start!</span><br>";
                                echo "----------------------------------------------- <br>";
                                $undefined_field[] = [
                                    "table_name" => strtolower($xml_array['table']['@attributes']['name']),
                                    "field_name" => $xml_field['@attributes']['name']
                                ];
                            }
                        }
                    }
                }
            }

        }
        $deleted_tables = [];
        if (!empty($undefined_field)){
            foreach ($undefined_field as $undefined)
            if (!in_array($undefined['table_name'], $deleted_tables)){
                $result = $connection->query("DROP TABLE IF EXISTS ".$undefined['table_name']);
                if ($result){
                    echo $undefined['table_name']."<span style='color: forestgreen; font-size: 18px;'> has changed successfully!</span>";
                }
                $deleted_tables[] = $undefined['table_name'];
            }
            unlink("../app/database.json");

        }
        return;

    }

    public function AddToTable($data): string{
        $keys = array_keys($data);
        $matching_keys = "";
        $counter = 1;
        foreach($keys as $key){
            if ($counter < count($keys)){
                $matching_keys = $matching_keys . $key . ", ";
                $counter ++ ;
            }else{
                $matching_keys = $matching_keys . $key;
            }

        }
        $values = array_values($data);
        $matching_val = "";
        $counter = 1;
        foreach($values as $val){
            if ($counter < count($values)){
                $matching_val = $matching_val ."'".$val."'". ", ";
                $counter ++ ;
            }else{
                $matching_val = $matching_val ."'".$val."'";
            }

        }
        $result = $this->connection->Execute("INSERT INTO {$this->tableName} ({$matching_keys}) VALUES ({$matching_val})");
        if($result){
            return True;
        } return False;

    }

    public function Remove($remove): string{
        $result = $this->connection->Execute("DELETE FROM {$this->tableName} WHERE $remove ");
        if($result){
            return true;
        }return false;
    }
    public function Update($key, $value, $condition): string
    {
        $query = "UPDATE {$this->tableName} SET {$key} = '{$value}' WHERE {$condition}";
        return $query;
//        if ($this->connection->Execute($query)) {
//            return true;
//        }
//        return false;
    }
    public function manualUpdate($query): bool
    {
        if ($this->connection->Execute($query)) {
            return true;
        }
        return false;
    }
    public function FindByKey($key, $value): bool|array
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE {$key} = '{$value}' LIMIT 1";
        $result = $this->connection->Execute($sql);
        if($result) {
            return $data = $result->fields;
        }
        return False;
    }
    public function GetAllData(): bool|array|object{
        $sql = "SELECT * FROM $this->tableName";
        $result = $this->connection->getAll($sql);
        if($result){
            return $result;
        }
        return False;

    }
    public function getManualData($sentSql): bool|array
    {
        $sql = "$sentSql";
        $result = $this->connection->getAll($sql);
        if ($result) {
            return $result;
        }
        return false;
    }
    public function insertOrUpdate($sql): bool
    {

        $result = $this->connection->Execute($sql);
        if ($result) {
            return true;
        }
        return false;
    }

}