<?php

/**
 * Class for connection to database
 *
 * @author Michal Katuščák
 */
class DB {
    
    
    // {{{ properties
    
    private $mysqli;
    
    private $sql_query;
    
    // }}}
    
    // {{{ ___construct()    
    /**
     * Connect to MySQLi database
     *
     * @param string $server   (localhost)
     * @param string $username Login to DB
     * @param string $password *****
     * @param string $database Name of databse
     *
     * @return int 1 if connection with MySQL is successful
     */
    
    public function __construct ($server, $username, $password, $database) 
    {
        $this->mysqli = @mysqli_connect($server, $username, $password) OR $GLOBALS['debuger']->add('Can\'t connect to database server',get_class($this).'::'.'__construct',1);
        @mysqli_select_db ($this->mysqli, $database) OR $GLOBALS['debuger']->add('Can\'t connect to database <em>'.$database.'</em>',get_class($this).'::'.'__construct',1);
        mysqli_query($this->mysqli, "SET CHARACTER SET utf8");
    }    
    
    // }}}
    
    // {{{ query()    
    /**
     * SQL
     *
     * @param string $sql Query
     *
     * @return int 1 if connection with MySQL is successful
     */
    
    public function query ($sql) 
    {
        $this->sql_query = mysqli_query($this->mysqli, $sql) OR $GLOBALS['debuger']->add('Bad SQL <em>'.$sql.'</em>',get_class($this).'::'.'query',1);
        $GLOBALS['debuger']->count_sql++;
    }    
    
    // }}}
    
    // {{{ fetch_array()    
    /**
     * MySQLi fetch array
     *
     * @return array [0]['row1']=>'text',[0]['row2']=>'text'
     */
    
    public function fetch_array () 
    {
        $num_row = 0;
        if ($result = $this->sql_query) {
            if (mysqli_num_rows($result) >= 1) {
                $fetch_array = Array();
                while ($row = mysqli_fetch_array($result)) {
                    foreach($row as $key=>$column) {
                        $fetch_array[$num_row][$key] = $column;
                    }
                    $num_row++;
                }
                mysqli_free_result($result);
                return $fetch_array;
            }
            return 0;
        }
    }    
    
    // }}}
    
}
