<?php
    namespace Utility\Traits;

    /**
     * This trait returns the array of the data necessary for pagination to take effect
     * @param array<int, array> $totalArray - The total assoc array of data to be be paginated e.g db while(assoc) data
     * @param int $pageLimit - The limit of the data to be shown per page
     * @return array<string, mixed>
     */
    trait Paginator
    {
        public static function getPagingData(array $totalArray, int $pageLimit) : array {
            $currentPage = 1;

            //avoid division by 0 error
            if ($pageLimit > 0) {
                $totalPages = count($totalArray) / $pageLimit;
            } else {
                $totalPages = 0;
            }
            
            //check if there is any page to display
            if ($totalPages > 0) {
                if (is_float($totalPages)) {
                    if ($totalPages < 1.0 ) {
                        $totalPages = 1;
                    } else {
                        $totalPages = intval($totalPages) + 1;
                    }
                }

                //check if you can go forward or it is just one page list
                if ($totalPages > 1) {
                    $forwardPage = 2;
                    $backwardPage = 1;
                } else {
                    $currentPage = $forwardPage = $backwardPage = 0;
                }

                //check the page value the user sets on the browser and set the currentPage to that value
                if (isset($_GET["page"])) {
                    $currentPage = intval($_GET["page"]);
                    
                    //check that it is not a one page list
                    if ($totalPages == 1) {
                        $backwardPage = $forwardPage = 0;
                        $currentPage = 1;
                    } elseif ($currentPage == $totalPages) {
                        $forwardPage = 0;
                        $backwardPage = $totalPages - 1;
                    } elseif ($currentPage > $totalPages) {
                        $currentPage = $totalPages;
                        $forwardPage = 0;
                        //check if you can go backs
                        if ($totalPages > 1) {
                            $backwardPage = $totalPages - 1;
                        } else {
                            $backwardPage = $totalPages = 0;
                        }

                    } elseif ($currentPage <= 1) {
                        $currentPage = 1;
                        $backwardPage = 0;

                        //check if he can go forward
                        if ($totalPages > 1) {
                            $forwardPage = 2;
                        } else {
                            $forwardPage = 1;
                        }
                        
                    }
                    elseif ($currentPage < $totalPages) {
                        $backwardPage = $currentPage - 1;
                        $forwardPage = $currentPage + 1;
                    }
                }

                //get the array to show on that page from the total db array of the query
                if ($totalPages == 1) {
                    $showArray = $totalArray;
                    $lastKey = array_key_last($totalArray);
                } else {
                    //get the last key in the total db array to be shown
                    $totalArrayLastKey = array_key_last($totalArray);

                    //supposed current page last key
                    $lastKey = ($currentPage * $pageLimit) - 1;
                    if ($lastKey > $totalArrayLastKey) {
                        $lastKey = $totalArrayLastKey;
                    }

                    //count down to the limit range
                    $countDownToLimitRange = $lastKey - $pageLimit;

                    //form the array to show starting from the first key known now to the last key
                    $showArrayBuff = [];
                    for ($i = $lastKey; $i > $countDownToLimitRange; $i--) { 
                        $showArrayBuff[] = $totalArray[$i];
                    }

                    $showArray = array_reverse($showArrayBuff);

                    //get the pages with complete items
                    if (is_float(count($totalArray)/$totalPages)) {
                        $completePages = $totalPages - 1;
                    } else {
                        $completePages = $totalPages;
                    }

                    //get the last page number of items
                    $completePageItems = $pageLimit * $completePages;
                    $lastPageItems = count($totalArray) - $completePageItems;

                    //if the last page has an incomplete number of items per page 
                    //get the remaining items from the page
                    if ($currentPage == $totalPages && $lastPageItems != 0) {
                        $showArrayBuff = [];
                        for ($i=0; $i < $lastPageItems; $i++) { 
                            $showArrayBuff[] = array_reverse($showArray)[$i];
                        }

                        $showArray = $showArrayBuff;
                    }
                
                }

                //get my last s/n to the table
                $lastPageSn = ($currentPage*$pageLimit - $pageLimit) + 1;

                // echo "totalPages". $totalPages;
                // print_r($showArray);
                // echo "lastPageSn" . $lastPageSn;
                // echo "lastKey" .$lastKey;
            } else {
                //export empty values
                $showArray = array();
                $lastPageSn = 0;
                $totalPages = 0;
                $backwardPage = 0;
                $forwardPage = 0;
            }

            return [
                "data" => $showArray,
                "current_page" => $currentPage,
                "last_page" => $totalPages,
                "total_pages" => $totalPages,
                "prev_page" => $backwardPage,
                "next_page" => $forwardPage
            ];
        }
    }
    