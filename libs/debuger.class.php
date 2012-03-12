<?php

/**
 * Capture errors
 *
 * Level One = Error (exits program)
 * Level Two = Warning (don't exits program)
 * Lever Three = Notice (don't exits program)
 * 
 * @author Michal Katuščák
 */
class Debuger {
    
    // {{{ properties
    
    private $error = Array();
    
    private $warning = Array();
    
    private $notice = Array();
    
    private $cancel = 0;
    private $time_start = 0;
    
    public $count_sql = 0;
    
    // }}}
    
    // {{{ __construct()   
    
    public function __construct () 
    {   
        $this->time_start = microtime();
    }    
    
    // }}}
    
    // {{{ add()    
    /**
     * Add error
     *
     * @param string $text
     * @param string $method
     * @param int    $level 1 - Error, 2 - Warning, 3 - Notice, (default: 3)
     *
     */
    
    public function add ($text, $method = '', $level = 3) 
    {   
        if ($this->cancel == 1) return 0;
        
        $method = ($method != '') ? ' in <strong>'.$method.'</strong>' : '';
        switch ($level) {
            case 1:
                $this->error[] = $text.$method;
                $this->cancel = 1;
                break;
            case 2:
                $this->warning[] = $text.$method;
                break;
            case 3:
                $this->notice[] = $text.$method;
                break;
        }
    }    
    
    // }}}
    
    // {{{ render()    
    /**
     * Render all errors
     * 
     * @return string All errors, warnings and notices
     */
    
    public function render () 
    {
        $text = '<h1>Debuger</h1>';
        
        $text .= '<p>Time script: '.(microtime()-$this->time_start).' second';
        
        $text .= '<p>Count SQL: '.$this->count_sql.' queries';
        
        if (isset($this->error[0])) {
            $text .= '<h2>Error</h2>';

            foreach ($this->error as $error) {
                $text .= '<p>'.$error;
            }
        }
        
        if (isset($this->warning[0])) {
            $text .= '<h2>Warning</h2>';

            foreach ($this->warning as $warning) {
                $text .= '<p>'.$warning;
            }
        }
        
        if (isset($this->notice[0])) {
            $text .= '<h2>Notice</h2>';

            foreach ($this->notice as $notice) {
                $text .= '<p>'.$notice;
            }
        }
        
        return $text;
    }    
    // }}}
    
}