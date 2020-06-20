<?php

    class Event {
        private $number;
        private $bill_account;
        private $balance;
        private $id;

        public $table = 'accounts';

        public static function get_pdo(){
            $_pdo;
            if (empty($_pdo)) {
                $_pdo = new PDO('mysql:host=localhost; dbname=agi', 'root', ''); 
            }
            return $_pdo;
        }

        public function add_event(){
            $this->number = $_GET['number'];  
            
            $this->bill_account = $_GET['bill'];  
            
            $this->balance = $_GET['balance'];    

            $sql = static::get_pdo()->prepare('INSERT INTO `' . $this->table . '` (`number`, `bill_account`, `balance`) VALUES (?,?,?);');
            $sql->execute(array($this->number, $this->bill_account, $this->balance));    
            
            return $this->read_from_db();
        }

        public function delete(){
            if (!empty($_GET['id'])){
                $sql = static::get_pdo()->prepare('SELECT id FROM `'. $this->table .'` WHERE id =' . $_GET['id']);             
                $sql->execute();    

                if ($object = $sql->fetchObject(static::class)) {
                    $sql = $this->get_pdo()->prepare('DELETE from`'.$this->table .'` WHERE `id` = ' . $_GET['id']. ';');
                    $sql->execute();
                    return $this->read_from_db();
                }
                else 
                    return "{Id Error}";                        
                
            }

            else return "{Error}";
            
        }    
        
        public function update_db(){
            if (!empty($_GET['id'])){ 
                $sql = static::get_pdo()->prepare('SELECT id, number, bill_account, balance FROM `'. $this->table .'` WHERE id =' . $_GET['id']);             
                $sql->execute(); 

                if ($object = $sql->fetchObject(static::class)) {

                    $this->number = isset($_GET['number']) ? trim($_GET['number']) :  $object->number;
                    $this->bill_account = isset($_GET['bill']) ? trim($_GET['bill']) : $object->bill_account;
                    $this->balance = isset($_GET['balance']) ? trim($_GET['balance']) : $object->balance;

                    $sql = static::get_pdo()->prepare('UPDATE `'.$this->table.'` SET `number`= ?, `bill_account`= ?, `balance`= ? where `id`= ? limit 1;');
                    $sql->execute(array($this->number, $this->bill_account, $this->balance, $_GET['id']));

                    return $this->read_from_db();
                }
                else return "{Id error}";
            }
            else 
                return "{Error}";
            
        }

        public function read_from_db(){                                  
            $sql = static::get_pdo()->prepare('SELECT id, number, bill_account, balance FROM `'. $this->table .'` ');             
            $sql->execute(); 

            $str = "{\n";

            while ($object = $sql->fetchObject(static::class)){
                $id = $object->id;
                $str .= "{id: " . $object->id . ", number: " . $object->number . ", bill: " . $object->bill_account . ", balance:" . $object->balance . "},\n";
            }

            $str = substr($str,0,-2);
            $str .= "\n}";

            return $str;
        }

       
        public function read_by_id(){
            if (isset($_GET['id'])) {
                $sql = static::get_pdo()->prepare('SELECT * FROM `' . $this->table . '` WHERE `id` =' . $_GET['id'] . ';');
                $sql->execute();

                    
                if ($object = $sql->fetchObject(static::class)) {    
                    $str = "{\n";
                    $str .= "{id: " . $object->id . ", number: " . $object->number . ", bill: " . $object->bill_account . ", balance:" . $object->balance . "}\n}";
                }
                else {
                    $str = "{Id error}";
                }
            }
            else {
                $str = "{Error}";
            }           

            return $str;
    
        }

        
    }
?>

