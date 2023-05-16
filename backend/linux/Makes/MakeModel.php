<?php
    namespace App\Models;

    use Utility\BaseModel;
    use App\Providers\AuthServiceProvider;

    class MakeModel extends BaseModel {
        /**
         * This is the created db table for this model
         * @var string
         */
        protected $table="";

        /**
         * These are the db columns that can be filled with data during insert to this model db table.
         */
        protected $fillable = [
            /**Enter you table db columns here else any column that is not here will not be filled during data insert */
            'time_created',
            'date_created'
        ];

    }
?>