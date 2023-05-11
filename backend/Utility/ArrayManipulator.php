<?php
    namespace Utility;
    
    class ArrayManipulator{
        /**
         * This function helps to implode an array glued with a string eg ["name", "age"] will be name,age
         */
        public static function implodeColsAsDbInsert(array $array) {
            return implode(',', $array);
        }

        /**
         * This function helps to spread array like ["name","age"] into 'name','age' so that it could be inserted to
         * DB
         * @param array $myarray - this is the array to be spread eg ["name","age"]
         * @return string this is the result returned eg   'name','age'
         */
        public static function implodeValuesAsDbInsert(array $myarray) : string {
            //remember that the values must be spread in the sql statement hence the below technique
            // this will help the exploded values to spread like 'val1','val2'... hence allowing insertion to db
            $myarray="{R=E=P=L=A=C=E}".implode("<=glue=>", $myarray)."{R=E=P=L=A=C=E}";
            $myarray=preg_replace("/<=glue=>/","','",$myarray);
            $myarray=preg_replace("/{R=E=P=L=A=C=E}/","'",$myarray);
            return $myarray;
        }
    }