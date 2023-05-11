<?php
    namespace Utility\Interfaces;

    interface CRUD {
        /**
         * The create action of a typical model
         * @param array<string, string> $dataArray - The assoc array of the model to be created in the db
         * @return boolean
         * @throws QueryException | MyException
         */
        public function create(array $dataArray) : bool;

        /**
         * The retrieve action of a typical model
         * @param string $condition - The condition to apply to database
         * @return array<int, array> - The model object
         */
        public function where(string $condition) : array;
        public function all() : array;

        /**
         * This is for updataing the model
         * @param array<string, string> $dataArray
         * @param string $where_set - The condition constraint set on the update
         * @return bool - The updated model response
         */
        public function update(array $dataArray, string $where_set) : bool;

        /**
         * This is the delete action of a model to be deleted from the database
         * @param string $where_set - The condition on the table to be deleted
         * @return true if the model is removed from the database or  does no longer exists from  the database
         * 
         */
        public function delete(string $where_set) : bool;
    }