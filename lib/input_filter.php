<?php

require("gump.class.php");

class InputFilterClass extends GUMP {

    /**
     * Determine if the provided value's minumun value
     *
     * Usage: '<index>' => 'min_val,2'
     *
     * @access protected
     * @param  string $field
     * @param  array $input
     * @return mixed
     */
    protected function validate_min_val($field, $input, $param = NULL) {

        if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }
        
        if ($input[$field] >= (int) $param) {
            return;
        }

        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }

    /**
     * Determine if the provided value's minumun value
     *
     * Usage: '<index>' => 'max_val,8'
     *
     * @access protected
     * @param  string $field
     * @param  array $input
     * @return mixed
     */
    protected function validate_max_val($field, $input, $param = NULL) {

        
        if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }

        if ($input[$field] <= (int) $param) {
            return;
        }

        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }

    /**
     * Determine if the provided value is in range
     *
     * Usage: '<index>' => 'posibble_vals,str'
     *
     * @access protected
     * @param  string $field
     * @param  array $input
     * @return mixed
     */
    protected function validate_posibble_vals($field, $input, $param = NULL) {


        if (!isset($input[$field]) || $input[$field] === "") {
            return;
        }


        if (is_array($input[$field])) {
            $isValid = 0;
            foreach ($input[$field] as $value) {

                if (isset($value) && $value !== "") {

                    if (is_string($value) || is_int($value)) {
                        if (strpos($param, $value) !== false) {
                            $isValid ++;
                        }
                    }
                }
            }
            if (count($input[$field]) === $isValid) {
                return;
            }
        } else {
            if (is_string($input[$field]) || is_int($input[$field])) {
                if (strpos($param, $input[$field]) !== false) {
                    return;
                }
            }
        }


        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }



    /**
     * Determine if the provided value is a valid date due to jquery's datepicker
     *
     * Usage: '<index>' => 'picker_date'
     *
     * @access protected
     * @param  string $field
     * @param  array $input
     * @return mixed
     */
    protected function validate_combo_date($field, $input, $param = NULL) {

        if (!isset($input[$field]) || $input[$field] === "") {
            return;
        }

        if (is_array($input[$field])) {
            $isValid = 0;
            foreach ($input[$field] as $value) {
                if ($this->validateDate($value)) {
                    $isValid ++;
                }
            }
            if (count($input[$field]) === $isValid) {
                return;
            }
        } else {
            if ($this->validateDate($input[$field])) {
                return;
            }
        }



        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }

    /**
     * Determine if the provided value is valid due to the jquery's input mask
     *
     * Usage: '<index>' => 'phone'
     *
     * @access protected
     * @param  string $field
     * @param  array $input
     * @return mixed
     */
    protected function validate_phone($field, $input, $param = NULL) {


        if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }

        $input[$field] = trim($input[$field]);
        $input[$field] = str_replace(' ', '', $input[$field]);
        $input[$field] = str_replace(")", '', $input[$field]);
        $input[$field] = str_replace("(", '', $input[$field]);
        $input[$field] = str_replace("-", '', $input[$field]);

//    if (preg_match("/^[0]{1}[0-9]{10}$/", $phone)) {

        if (preg_match("/^[0-9]{10}$/", $input[$field])) {
            return;
        }

        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }



    /**
     * Determine if the provided value is valid due to its conditional values
     *
     * Usage: '<index>' => 'conditional_required'
     *
     * @access protected
     * @param  string $field
     * @param  array $input
     * @return mixed
     */
    protected function validate_exact_val($field, $input, $param = NULL) {

        if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }

            if ($input[$field] === $param) {
                return;
            }
        
        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }


//    functions************************************


//    functions************************************

    protected function validate_date($field, $input, $param = NULL) {

        if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }
        
        $datetime = DateTime::createFromFormat('d/m/Y', $input[$field]);
        
        if (($datetime && $datetime->format('d/m/Y') == $input[$field]) === true) {
                return ;
        }
                
        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }




protected function validate_name($field, $input, $param = NULL) {
    
    if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }
    
    if ( preg_match("/^[A-Za-z1-9\sçığöşüÇİĞÖŞÜ`._'-]+$/", $input[$field]) ){
        return;
    }
            
    return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
}


protected function validate_tl($field, $input, $param = NULL) {
    
    if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }
    
    if ( preg_match("/^(([0-9]{1,3})(?:([.])\d{3})*?)[,]([0-9]{2})$/", $input[$field]) ){
        return;
    }
            
    return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
}

 protected function validate_date_range($field, $input, $param = NULL) {
       
     
     if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }
     
       $date = DateTime::createFromFormat('d/m/Y', $input[$field])->format('Y-m-d');
       $params = explode('-', $param);
       
       if(isset($param)){
       
        if($params[0] == 'today'){

            $minDate = date("Y-m-d");

        }else{
            $minDate = DateTime::createFromFormat('d/m/Y', $params[0])->format('Y-m-d');
        }

        $maxDate = DateTime::createFromFormat('d/m/Y', $params[1])->format('Y-m-d');

        if($date >= $minDate || $date <= $maxDate){
            return;
        }
        
       }else{
           if($date >= date("Y-m-d")){
            return;
        }
        
       }      
        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }

 protected function validate_min_date($field, $input, $param = NULL) {
       
     if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }
        
        
       $date = DateTime::createFromFormat('d/m/Y', $input[$field])->format('Y-m-d');
       
        if(!$param){

            $minDate = date("Y-m-d");

        }else{
            $minDate = DateTime::createFromFormat('d/m/Y', $param)->format('Y-m-d');
        }

        if($date >= $minDate){
            return;
        }
     
        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }    
    
    
    protected function validate_max_date($field, $input, $param = NULL) {
       
        if (!isset($input[$field]) || trim($input[$field]) === "") {
            return;
        }
        
       $date = DateTime::createFromFormat('d/m/Y', $input[$field])->format('Y-m-d');
       
        $maxDate = DateTime::createFromFormat('d/m/Y', $param)->format('Y-m-d');

        if($date <= $maxDate){
            return;
        }
     
        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule' => __FUNCTION__,
            'param' => $param
        );
    }   
    
}


// EOC
?>