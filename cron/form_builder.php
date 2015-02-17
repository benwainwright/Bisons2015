<?php/*
error_reporting(-1);
ini_set('display_errors', 'On');

Class Wordpress_Form 
{
    
    private $fieldsets;
    private $fields;
    
    function fieldset ( $name, $legend = false, $fieldsetinfo = false )
    {
        if ($legend) $this->fieldsets[$name]['legend'] = $legend;
        if ($fieldsetinfo) $this->fieldsets[$name]['fieldsetinfo'] = $fieldsetinfo;
    }
        
    function input_box( $fieldset, $name, $label, $classes = false, $forminfo = false )
    {
        $output .= "<div>";
        $output .= "<label for='$name'>$label</label>";
        $output .= "<input ";
        $output .= $classes ? "class='$classes' " : '';
        $output .= "name='$name' id='$name' />";
        $output .= $forminfo ? "<p class='forminfo'>$forminfo</p>" : null ;
        $output .= "</div>";
        $this->$fields[$fieldset][] = $output;
    }
    
    function form_output ( )
    {
        echo "test";
        foreach ( $this->fields as $fieldset_name => $array )
        {
            echo "<fieldset>";
            echo "</fieldset>";
        }
    }
}


$form = new Wordpress_Form();
$form->fieldset ( 'fieldsetone', 'legend');
$form->input_box ( 'fieldsetone', 'text1', 'This is a text box');
$form->form_output();
*/
?>

<!DOCTYPE HTML>
<html>
<head></head>
<body><?php echo "Hello world"; ?></body>
</html>


