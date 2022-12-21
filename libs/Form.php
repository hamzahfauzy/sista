<?php

class Form
{
    static function input($type,$name,$attribute = [])
    {
        $attr = '';
        $vals = isset($attribute['value']) ? $attribute['value'] : '';
        if($type == 'date' && !isset($attribute['value'])) $attribute['value'] = date('Y-m-d');
        foreach($attribute as $key => $value)
            $attr .= " $key='$value'";
        
        if($type == 'textarea')
        {
            return self::textarea($name, $vals, $attr);
        }

        if(substr($type,0,8) == 'checkbox')
        {
            $types = explode(':',$type);
            $options = $types[1];
            $vals = explode(',',$value);
            
            if(substr($type, 8,3) == 'obj')
            {
                $obj_array = explode(',',$options);
                $options = $obj_array[0];

                $conn = conn();
                $db   = new Database($conn);
                $datas = $db->all($options);
                $options = $datas;
                $group = "";
                foreach($options as $option)
                {
                    $group .= self::checkbox($option->{$obj_array[2]}, $name, $option->{$obj_array[1]}, in_array($option->{$obj_array[1]},$vals));
                }
            }
            else
            {
                $options = explode('|',$options);
                $group = "";
                foreach($options as $option)
                    $group .= self::checkbox($option, $name, $option, in_array($option,$vals));
            }
            
            return "<div>$group</div>";
        }

        $lists = "";
        if(substr($type,0,7) == 'options')
        {
            $types = explode(':',$type);
            $options = $types[1];
            
            if(substr($type, 8,3) == 'obj')
            {
                $obj_array = explode(',',$options);
                $options = $obj_array[0];
                $params = [];
                if(isset($obj_array[3]) && isset($obj_array[4]))
                {
                    $params[$obj_array[3]] = $obj_array[4];
                }
                
                if(isset($obj_array[5]) && isset($obj_array[6]))
                {
                    $params[$obj_array[5]] = $obj_array[6];
                }

                $conn = conn();
                $db   = new Database($conn);
                $datas = $db->all($options,$params);
                $options = $datas;
                $lists .= "<option value=''>- Pilih -</option>";
                foreach($options as $option)
                {
                    $class = $obj_array[0] == 'kelurahan' ? 'class="kec-'.$option->kecamatan_id.'"' : '';
                    $class = $obj_array[0] == 'lingkungan' ? 'class="kel-'.$option->kelurahan_id.'"' : $class;
                    $lists .= "<option value='".$option->{$obj_array[1]}."' ".($option->{$obj_array[1]}==$value?'selected=""':'')." ".$class.">".$option->{$obj_array[2]}."</option>";
                }
            }
            elseif(substr($type, 8,3) == 'cus')
            {
                $obj = str_replace("options-cus:","",$type);
                $options = json_decode($obj);
                $lists .= "<option value=''>- Pilih -</option>";
                foreach($options as $key => $val)
                {
                    $lists .= "<option value='".$key."' ".($key==$value?'selected=""':'').">".$val."</option>";
                }
            }
            else
            {
                $options = explode('|',$options);
                foreach($options as $option)
                    $lists .= "<option value='$option' ".($option==$value?'selected=""':'').">$option</option>";
            }
            
            return self::options($name, $lists, $attr);
        }

        if($type == 'number')
        {
            $attr .= " step='any'";
        }

        return self::text($type,$name,$attr);
    }

    static function text($type,$name, $attr = "")
    {
        return "<input type='$type' name='$name' $attr>";
    }

    static function textarea($name, $value, $attr = "")
    {
        return "<textarea name='$name' $attr>$value</textarea>";
    }

    static function options($name, $lists, $attr = "")
    {
        return "<select name='$name' $attr>$lists</select>";
    }

    static function checkbox($label, $name, $value, $checked = false)
    {
        $attr = " value='$value' ".($checked?'checked':'');
        return "<label style='font-weight:400'>".self::text('checkbox', $name.'[]', $attr)." $label</label><br>";
    }

    static function getData($type, $index)
    {
        if(!$index) return '';
        if(substr($type,0,7) == 'options')
        {
            $types = explode(':',$type);
            $options = $types[1];
            if(substr($type, 8,3) == 'obj')
            {
                $obj_array = explode(',',$options);
                $options = $obj_array[0];

                $conn = conn();
                $db   = new Database($conn);
                $data = $db->single($options,[
                    $obj_array[1] => $index
                ]);
                return $data->{$obj_array[2]};
            }
        }
        return $index;
    }
}