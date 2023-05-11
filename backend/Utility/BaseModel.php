<?php
    namespace Utility;

    use Utility\Interfaces\CRUD;
    use Utility\Traits\Paginator;
    use Utility\Traits\ImageUploader;
    use App\Http\Exceptions\MyException;
    use App\Http\Exceptions\QueryException;
    use App\Providers\AuthServiceProvider;
    
    abstract class BaseModel extends Db  {
        use Paginator, ImageUploader;

        /**
         * This is same as $this->all() and $this->where() but this time aroud with pagination info
         * @return array<string, mixed>
         */
        public function paginate($limit = 25, $where_set = '1=1') {

            if ($sql = mysqli_query(self::dbConnect(), "SELECT * FROM $this->table WHERE $where_set")) {
                $totalRows = [];
                while($rowArray = mysqli_fetch_assoc($sql)) {
                    $totalRows[] = $rowArray;
                }

                mysqli_close(static::dbConnect()); //safely close your db before you return
                return static::getPagingData($totalRows, $limit);

            } else {
                throw new QueryException("Unable to execute query");
            }
        }

        /**
         * This gets the desired column from the model table
         * @param string $get_col - The column to be returned
         * @return mixed - The database column value of the query
         */
        public function get($get_col, string $where_set) {
            return parent::getFromTb($this->table, $get_col, $where_set);
        }

         /**
         * This credits the desired column of the model table
         * @param string $col - The column to be credited
         * @return boolean
         */
        public function credit($amount, $col, string $where_set) : bool {
            return parent::creditTb($this->table, $amount, $col, $where_set);
        }

         /**
         * Helps to debit amount from the model specified column
         * @param string $col - The column to be debited 
         * @return bool;
         * 
         */
        public function debit($amount, $col, string $where_set) : bool{
            return parent::debitTb($this->table, $amount, $col, $where_set);
        }

        /**
         * Checks if the model exists in this database defaul
         * @return bool;
         */
        public function exists($search_value, $col_key=  AuthServiceProvider::AUTH_CHECK_COLUMNS[0]): bool {
            return parent::modelExists($search_value, $this->table, $col_key);
        }

        /**
         * Implemented from CRUD
         */
        public function create(array $dataArray) : bool {
            if (count($dataArray) < 1) {
                throw new MyException("You can not create an empty data to $this->table table");
            }

            //add/override the time and date_created respectively to the field
            $dataArray['time_created'] = $this->timeNow;
            $dataArray['date_created'] = $this->dateNow;

            //remove the csrf_token key already verified by you on your controller with Db::csrf_verify($csrf_token)
            unset($dataArray["csrf_token"]);
            
            //check that the keys exists in the db table we are inserting the data to
            $dbColsConfigArr = parent::getDBTables()[$this->table];
            $cols = [];
            $vals = [];
            foreach ($dataArray as $key => $value) {
                if (is_int($key)) {
                    throw new MyException("Ensure that Your are passing assoc array<string, string> as the arg to create.");
                }
                
                if (!in_array($key, $dbColsConfigArr)) {
                    throw new MyException("Column name `$key` not configured in global Dbtables of $this->table.");
                }

                if (!in_array($key, $this->fillable)) {
                    //skip it and reloop from this point
                    continue;
                }

                //form the key and values to be inserted
                $cols[] = $key;
                $vals[] = filter($value);
            }


            //form the 
            $cols = ArrayManipulator::implodeColsAsDbInsert($cols);
            $vals = ArrayManipulator::implodeValuesAsDbInsert($vals);

            if ($sql = mysqli_query(self::dbConnect(), "INSERT INTO $this->table ($cols) VALUES ($vals)")) {
                
                mysqli_close(static::dbConnect()); //safely close your db before you return
                return true;

            } else {
                throw new QueryException("Unable to execute query");
            }
        }

        /**
         * Implemented from CRUD
         */
        public function where(string $condition) : array {
             
            if ($sql = mysqli_query(self::dbConnect(), "SELECT * FROM $this->table WHERE $condition")) {
                $totalRows = [];
                while($rowArray = mysqli_fetch_assoc($sql)) {

                    $totalRows[] = $rowArray;
                }

                mysqli_close(static::dbConnect()); //safely close your db before you return
                return $totalRows;

            } else {
                throw new QueryException("Unable to execute query");
            }
        }

        /**
         * Implemented from CRUD
         */
        public function all() : array {
            if ($sql = mysqli_query(self::dbConnect(), "SELECT * FROM $this->table")) {
                $totalRows = [];
                while($rowArray = mysqli_fetch_assoc($sql)) {

                    $totalRows[] = $rowArray;
                }

                mysqli_close(static::dbConnect()); //safely close your db before you return
                return $totalRows;

            } else {
                throw new QueryException("Unable to execute query");
            }
        }

        /**
         * Implemented from CRUD
         */
        public function update(array $dataArray, string $where_set) : bool {
            //check that the keys exists in the db table we are inserting the data to
            $dbColsConfigArr = parent::getDBTables()[$this->table];

            $settings = '';

            foreach ($dataArray as $key => $value) {
                if (is_int($key)) {
                    throw new MyException("Ensure that Your are passing assoc array<string, string> as the args to update.");
                }
                
                if (!in_array($key, $dbColsConfigArr)) {
                    throw new MyException("Column name `$key` not configured in global Dbtables of $this->table.");
                }

                if (!in_array($key, $this->fillable)) {
                    //skip it and reloop from this point
                    continue;
                }

                //form the key and values settings to be updated

                //Note: if this column is not in fillable, it will not be in settings
                $settings .= "$key='$value',";
            }

            //Avoid the last comma in the setting of the sql set setup
            $settings = preg_replace("/,$/", '', $settings);

            if (empty($settings)) {
                throw new MyException("No column found to update from matching your data and fillable.");
            }

            mysqli_close(static::dbConnect()); //safely close your db before you return
            return parent::updateTb($this->table, $settings, $where_set);
        }

        /**
         * Implemented the CRUD interface
         */
        public function delete(string $where_set) : bool {
            if ($sql = mysqli_query(self::dbConnect(), "DELETE FROM $this->table WHERE $where_set")) {

                mysqli_close(static::dbConnect()); //safely close your db before you return
                return true;
            } else {
                throw new QueryException("Unable to execute query");
            }

        }
    }
    