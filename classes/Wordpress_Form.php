<?php

/**
  * Class to simplify the creation and processing of HTML forms in Wordpress.
  * @author Ben Wainwright bwainwright28@gmail.com
  * @copyright Ben Wainwright 2014
  * @license GPL
  * @license http://opensource.org/licenses/GPL-3.0
  */
Class Wordpress_Form 
{
    private $fieldsets;
    private $fields;
    private $id;
    private $form_method;
    private $submit_text;
    private $row_tag;
    private $has_file_uploads;
    private $meta_array;
    private $name_lists;
    private $errors; 
    private $use_fieldsets;
    private $label_parent_tag;
    private $field_parent_tag;
    private $html_before_fields_start;
    private $html_after_fields_end;
    private $forminfo_tag;
    private $no_form_tag;
    private $inner_tags;
    private $forminfo_tag_classes;
    private $submit_button_classes;

    
    /**
     * Constructor method. Sets defaults and initializes variables
     * @param string post_type Wordpress post type that this form is linked to
     * @param string post_title_field Which field will contain the post title
     * @param string form_method HTTP method for the <form> element
     * @param string submit_text Label text for the Submit button
     * @param string row_tag kind of HTML tag to enclose field rows initializes
     * @param string form_type Either 'submission' or 'edit'. The former indicating that the form simply submits data into the system, the latter indicating that you can return to it and edit the data after the fact
     */
    function __construct( $post_type, $post_title_field, $form_method, $submit_text, $html_id, $row_tag = null, $form_type = null )
    {
        $this->fields = array();
        $this->html_id = $html_id;
        $this->form_method = $form_method; 
        $this->post_id = $post_id;
        $this->submit_text = $submit_text;
        $this->row_tag = $row_tag ? $row_tag : "div" ;
        $this->has_file_uploads = false;
        $this->meta_array = array();
        $this->file_array = array();
        $this->name_list = array();
        $this->errors = array();
        $this->form_type = $form_type ? $form_type : 'submission';
        $this->post_type = $post_type;
        $this->post_title_field = $post_title_field;     
        $this->validation_regexes = array(
              'notblank'	=>	'/.+/'
        );
        $this->use_fieldsets = true;
        $this->label_parent_tag = false;
        $this->field_parent_tag = false;
        $this->html_before_fields_start = false;
        $this->html_after_fields_end = false;
	  $this->forminfo_tag = "p";
        $this->forminfo_tag_classes = array ('forminfo');
        $this->no_form_tag = false;
        $this->inner_tags = array();
        $this->submit_button_classes = false;   
    }
    
    /**
     * The forminfo tag appears after the input element inside the field_parent_tag. Use this function to set what HTML element will be used and to add classes to it
     * @param string name The HTML tag name
     * @param string|array classes HTML classes to be assigned to the forminfo tag
     */
    function set_forminfo_tag ( $name, $classes = false )
    {
          $this->forminfo_tag = $name;
          if ( $classes ) $this->forminfo_tag_classes = is_array ( $classes ) ? $classes : array ( $classes );
    }
    
    /**
     * Use this function to add extra HTML elements inside the FORM tag
     * @param string name The HTML tag name
     * @param string|array classes HTML classes to be assigned to the extra tag
     * @param string id HTML id to be assigned to the extra tag
     */
    function add_inner_tag ( $tag, $classes = false, $id = false )
    {
          if ( $classes ) $classes = is_array ( $classes ) ? $classes : array ( $classes );
          $tag = array ( 'tagname' => $tag, 'id' => $id, 'classes' => $classes );
          array_push ( $this->inner_tags, $tag );
    }
    
    function set_row_tag ( $name )
    {
    	 $this->row_tag = $name;     
    }
      
    function set_label_parent_tag ( $name )
    {
    	 $this->label_parent_tag = $name;     
    }

    function set_field_parent_tag ( $name )
    {
    	 $this->field_parent_tag = $name;     
    }
    
    function set_submit_button_classes ( $classes )
    {
         $this->submit_button_classes = is_array ( $classes ) ? $classes : array ( $classes );
    }
     
    function set_html_before_fields_start ( $html )
    {
    	 $this->html_before_fields_start = $html;     
    }
      
    function set_html_after_fields_end ( $html )
    {
    	 $this->html_after_fields_end = $html;     
    }
      
    function set_no_form_tag ( )
    {
          $this->no_form_tag = true;
    }
      
    // Set fieldset information and store it in the class fieldsets array
    function add_fieldset ( $name, $legend = false, $fieldsetinfo = false )
    {
        if ($legend) $this->fieldsets[$name]['legend'] = $legend;
        if ($fieldsetinfo) $this->fieldsets[$name]['fieldsetinfo'] = $fieldsetinfo;
    }
    
      
    function set_notempty_message ( $message )
    {
          $this->notempty_message = $message;
          
    }
      
    private function validate ( $val, $type, $message )
    {
		return preg_match ( $this->validation_regexes[ $type ], $val ) ? true : false;
    }

      
    function add_textarea ( $fieldset, $name, $label, $classes = false, $forminfo = false, $value = false )
    {
        
        $fieldset = $fieldset ? $fieldset : 'none';
		
        $this->check_uniqueness ( $name );
          
        // Add to name list for uniqueness checks
        array_push ( $this->name_list, $name );
        
        // Construct the HTML
        $fieldhtml = $this->create_input_html( $name, $label, 'textarea', $classes, $forminfo, $value );
          
        // If the fieldset array is not already create, initialize it as an array
        if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
          
        // Add the html to the end of the array
        array_push( $this->fields[$fieldset], array( 'html' => $fieldhtml ) );
        
        // If the page is a submitted form and the nonce is correct, save the meta information ready to be updated.  
        if ( $_POST ) $this->meta_array[$name] = $_POST[$name];
    }
    
    function add_static_text ( $fieldset, $name, $label, $classes = false, $forminfo = false, $value = false )
    {
            
        $output = "";
        $output .= $this->label_parent_tag ? "<$this->label_parent_tag>" : null;
        $output .= "<label for='$name'>$label</label>";
        $output .= $this->label_parent_tag ? "</$this->label_parent_tag>" : null;
        $output .= $this->field_parent_tag ? "<$this->field_parent_tag>" : null;
        $output .= $value;
        $output .= $this->field_parent_tag ? "</$this->field_parent_tag>" : null;
        // If the fieldset array is not already create, initialize it as an array
        if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
          
        // Add the html to the end of the array
        array_push( $this->fields[$fieldset], array( 'html' => $output )  );
    }
    
    function add_text_input ( $fieldset, $name, $label, $classes = false, $forminfo = false, $value = false )
    {
        
        $fieldset = $fieldset ? $fieldset : 'none';
		
        $this->check_uniqueness ( $name );
          
        // Add to name list for uniqueness checks
        array_push ( $this->name_list, $name );
        
        $labelclass = false;
          
        // Construct the HTML
        $fieldhtml = $this->create_input_html( $name, $label, 'text', $classes, $forminfo, $labelclass, $value );
          
        // If the fieldset array is not already create, initialize it as an array
        if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
          
        // Add the html to the end of the array
        array_push( $this->fields[$fieldset], array( 'html' => $fieldhtml )  );
        
        // If the page is a submitted form and the nonce is correct, save the meta information ready to be updated.  
        if ( $_POST ) $this->meta_array[$name] = $_POST[$name];
    }
      
    function add_password_input ( $fieldset, $name, $label, $classes = false, $forminfo = false, $value = false )
    {
        
        $fieldset = $fieldset ? $fieldset : 'none';
		
        $this->check_uniqueness ( $name );
          
        // Add to name list for uniqueness checks
        array_push ( $this->name_list, $name );
        
        $labelclass = false;
        
        // Construct the HTML
        $fieldhtml = $this->create_input_html( $name, $label, 'password', $classes, $forminfo, $labelclass, $value);
          
        // If the fieldset array is not already create, initialize it as an array
        if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
          
        // Add the html to the end of the array
        array_push( $this->fields[$fieldset], array( 'html' => $fieldhtml ) );
        
        // If the page is a submitted form and the nonce is correct, save the meta information ready to be updated.  
        if ( $_POST ) $this->meta_array[$name] = $_POST[$name];
    }
    function add_hidden_field ( $fieldset, $name, $value, $classes = false )
    {
        $fieldset = $fieldset ? $fieldset : 'none';
        
        $this->check_uniqueness ( $name );
          
        // Add to name list for uniqueness checks
        array_push ( $this->name_list, $name );
        
        $labelclass = false;
          
        // Construct the HTML
        $fieldhtml = $this->create_input_html( $name, $label, 'hidden', $classes, false, false, $value );
          
        // If the fieldset array is not already created, initialize it as an array
        if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
          
        // Add the html to the end of the array
        array_push( $this->fields[$fieldset], array( 'html' => $fieldhtml )  );
        
    }
    function add_list_box ( $fieldset, $name, $label, $options, $classes = false, $forminfo = false,  $value = false )
    {
          $fieldset = $fieldset ? $fieldset : 'none';
          
          $this->check_uniqueness ( $name );
          
          // Add to name list for uniqueness checks
          array_push ( $this->name_list, $name );
          
          $output = "";
          $output .= $this->label_parent_tag ? "<$this->label_parent_tag>" : null;
          $output .= "<label for='$name'>$label</label>";
	    $output .= $this->label_parent_tag ? "</$this->label_parent_tag>" : null;
          $output .= $this->field_parent_tag ? "<$this->field_parent_tag>" : null;
          $output .= "<select";
          $output .= $classes ? " class='$classes'" : '';
          $output .= " name='$name' id='$name'>";
          foreach ( $options as $key => $option )
	    $output .= "<option value='$key'>$option</option>";     
          $output .= "</select>";
          
          if ( $forminfo )
          {
              
              $output .= "<$this->forminfo_tag";
        	  $output .= $this->forminfo_tag_classes ? " class='".implode ( ' ', $this->forminfo_tag_classes )."'" : null;
              $output .= ">$forminfo</$this->forminfo_tag>";
          }
          $output .= $this->field_parent_tag ? "</$this->field_parent_tag>" : null;
          
          // If the fieldset array is not already create, initialize it as an array
          if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
          
          // Add the html to the end of the array
          array_push( $this->fields[$fieldset], array( 'html' => $output ) );
        
          // If the page is a submitted form and the nonce is correct, save the meta information ready to be updated.  
          if ( $_POST ) $this->meta_array[$name] = $_POST[$name];

    }
      
    function add_captcha ( $fieldset, $name, $label, $img_src, $classes = false, $forminfo = false )
    {
          
          $fieldset = $fieldset ? $fieldset : 'none';
          
          $this->check_uniqueness ( $name );
          
          // Add to name list for uniqueness checks
          array_push ( $this->name_list, $name );
	    
          $output = "";
          $output .= $this->label_parent_tag ? "<$this->label_parent_tag>" : null;
          $output .= "<label for='$name'>$label</label>";
	      $output .= $this->label_parent_tag ? "</$this->label_parent_tag>" : null;
          $output .= $this->field_parent_tag ? "<$this->field_parent_tag>" : null;
          $output .= "<input type='text'";
          $output .= $classes ? " class='$classes'" : ''; 
          $output .= " name='$name' id='$name' />";
          $output .= "<img src='$img_src'";
          $output .= $classes ? " class='$classes'" : '';
          $output .= ' />';
          
          if ( $forminfo )
          {
              
              $output .= "<$this->forminfo_tag";
        	  $output .= $this->forminfo_tag_classes ? " class='".implode ( ' ', $this->forminfo_tag_classes )."'" : null;
              $output .= ">$forminfo</$this->forminfo_tag>";
          }
          $output .= $this->field_parent_tag ? "</$this->field_parent_tag>" : null;
          
          // If the fieldset array is not already create, initialize it as an array
          if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
          
          $field_array = array ( 'html' => $output );
        
          // If the page is a submitted form and the nonce is correct, save the meta information ready to be updated.  
          if ( $_POST ) 
          {
                if ( $_POST [ $name ] != $_SESSION['digit'] )
                {
          	    	   $field_array['error'] = 'CAPTCHA validation failed. Please retry...';
                }
          }

          // Add the html to the end of the array
          array_push( $this->fields[$fieldset], $field_array );

    }
      
    function add_file_upload ( $fieldset, $name, $label, $attachment_id_metafield_name = null, $classes = false, $forminfo = false )
    {
          
        $fieldset = $fieldset ? $fieldset : 'none';
          
        $this->check_uniqueness ( $name );

        // Indicate that the form now includes file uploads so that the form type is set correctly
        $this->has_file_uploads = true;
          
        // Construct the HTML
        $fieldhtml = $this->create_input_html( $name, $label, 'file', $classes, $forminfo );

        // If the fieldset array is not already create, initialize it as an array
        if ( ! isset( $this->fields[$fieldset] ) ) $this->fields[$fieldset] = array();
        
        $field_array = array( 'html' => $fieldhtml );
          
        switch ( $_FILES[$name]["error"] )
        {
              case UPLOAD_ERR_INI_SIZE: $field_array['error'] = 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; break;
              case UPLOAD_ERR_FORM_SIZE : $field_array['error'] =  'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.'; break;  
              case UPLOAD_ERR_PARTIAL : $field_array['error'] =  'The uploaded file was only partially uploaded.'; break;  
              case UPLOAD_ERR_NO_TMP_DIR : $field_array['error'] =  'Missing a temporary folder.'; break;
              case UPLOAD_ERR_CANT_WRITE : $field_array['error'] =  'Failed to write file to disk.'; break;
              case UPLOAD_ERR_EXTENSION : $field_array['error'] = 'A PHP extension stopped the file upload.'; break;
        }


        // Add the html to the end of the array
        array_push( $this->fields[$fieldset], $field_array);   


        // Save field name if this is a POST
        if ( $_POST ) $this->file_array[$name] = $attachment_id_metafield_name;
        
    }
      

    /**
     * Returns true if there are any errors associated with any of the form fields. False if not.
     */
    function is_errors( )
    {
          // Loop through each field, return true if an error is found in any field array
          foreach ( $this->fields as $fieldset )
          {
                foreach ( $fieldset as $field )
                {
                      if ( isset ( $field['error'] ) )
                      {
                          return true;
                      }
                }
          }
          // If we get to the end without finding an error, return false
	    return false;
    }
      
      
    function not_using_fieldsets()
    {
          $this->use_fieldsets = false;
    }
     
    /**
     * If there are no errors, save all the metadata into the Wordpress post.
     */    function submit_form ( )
    {
        // If the nonce passes and there is no errors
        if ( wp_verify_nonce( $_POST['nonce'], 'wordpress_form_submit' ) && ! $this->is_errors() ) 
	  {

		// Submit the post
            if ($this->form_type == 'submission' )
            {
                  $post = array(
                        'post_title'    => $_POST[$this->post_title_field],
                        'post_type'     => $this->post_type,
                        'post_status'   => 'pending',
                  );
        	      $this->post_id = wp_insert_post( $post );
            }
              
            // Update all the meta fields
            foreach ( $this->meta_array as $key => $value )
                
                update_post_meta( $this->post_id, $key, esc_attr ( $value ) );

            require_once( ABSPATH . 'wp-admin/includes/image.php' );  
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
              
            // Let wordpress handle file uploads           
            foreach ( $this->file_array as $name => $attachment_id_metafield_name )
            {
                  
                $attachment_id = media_handle_upload( $name, $this->post_id );
                if ( $attachment_id_metafield_name ) 
                      update_post_meta( $this->post_id, $attachment_id_metafield_name, $attachment_id );
            }
                
          	return true;
              
        }
              
        else return false; 
    }
      
    /**
     * Output the HTML to this form
     * @param bool echo If set to true, function will use the PHP 'echo' method. If not HTML will be returned from the method.
     */
    function form_output ( $echo = true )
    {
          
        $output = '';

        if ( ! $this->no_form_tag )
        { 
              $output .= "<form";
              $output .= $this->form_method ? " method='".$this->form_method."'" : '';  
              $output .= $this->html_id ? " id='".$this->html_id."'" : '';  
              $output .= $this->has_file_uploads ? " enctype='multipart/form-data'" : '';
              $output .= ">";
        }
        
        foreach ($this->inner_tags as $tag )
        {
              $output .= "<";
              $output .= $tag['tagname'];
	        $output .= $tag['id'] ? " id='".$tag['id']."'" : null;
        	  $output .= $tag['classes'] ? " class='".implode ( ' ', $tag['classes'] )."'" : null;
              $output .= ">";
        }
          
        foreach ( $this->fields as $fieldset_name => $fields )
        {
              if ( $this->use_fieldsets )
              {
                    $output .= "<fieldset id='$fieldset_name'>";
                    if ( $this->fieldsets[$fieldset_name]['legend'] ) $output .= "\t\t<legend>".$this->fieldsets[$fieldset_name]['legend']."</legend>\n";                
                    if ( $this->fieldsets[$fieldset_name]['fieldsetinfo'] ) $output .= "\t\t<p class='info'>".$this->fieldsets[$fieldset_name]['fieldsetinfo']."</p>\n";
              }
              
              foreach ( $fields as $field ) 
              {

                    $output .= $this->row_tag ? "\t\t<".$this->row_tag.">" : '';
                    $output .= $field['html'];
                    $output .= $this->row_tag ? "\t\t</".$this->row_tag.">" : '';

              }
            if ( $this->use_fieldsets ) $output .= "</fieldset>";
        }
        $tags = array_reverse ( $this->inner_tags );
        foreach ( $tags as $tag ) $output .= "</".$tag['tagname'].">";   
        $output .= "<button type='submit'";
        $output .= $this->submit_button_classes ? " class='".implode ( ' ', $this->submit_button_classes)."'" : null;
        $output .= ">$this->submit_text</button>";
        $output .= "<input type='hidden' name='nonce' value='".wp_create_nonce( 'wordpress_form_submit' )."' />";
        $output .= "<input type='hidden' name='wp_form_id' value='".$this->html_id."' />"; 
        if ( ! $this->no_form_tag ) $output .= "</form>\n";   
        if ( $echo ) echo $output;
        else return $output;
    }
    

    private function check_uniqueness( $name )
    {
        if ( in_array ( $name, $this->name_list ) ) throw new exception ('Field names must be unique.');  
    }
      
    /**
     * Generate the HTML associated with each input element
     * @param string name The field name will be applied to the 'for' attribute of the label as well as the 'id' and 'name' attribute of the input
     * @param string label The text for the HTML label tag
     * @param string type What type of input tag
     * @param string|array HTML classes to be applied to the input. Can be either an array of strings or a single strange
     * @param string forminfo Text for the forminfo tag
     * @param string value text for the 'value' attribute
     */


    private function create_input_html( $name, $label, $type, $classes = false, $forminfo = false, $labelclasses = false, $value = false )
    {
        
          
        if ( $this->is_errors() ) $value = $_POST[ $name ];
        
        $output = "";
        
        if ( $type != 'hidden' )
        {
            $output .= $this->label_parent_tag ? "<$this->label_parent_tag>" : null;
            $output .= "<label for='$name'>$label</label>";
	        $output .= $this->label_parent_tag ? "</$this->label_parent_tag>" : null;
            $output .= $this->field_parent_tag ? "<$this->field_parent_tag>" : null;
        }
        if ( $type == 'textarea' ) $output .= "<textarea";
        else $output .= "<input type='$type'";
        $output .= $classes ? " class='$classes'" : '';
        if ( $type != 'textarea' ) $output .= $value ? " value='$value'" : '';
        $output .= " name='$name' id='$name'";
        
        if ( $type == 'textarea' )
        {
        	$output .= ">";
        	$output .= $value ? $value : '';
            $output .= "</textarea>";
        }
        else $output .="/>";
        
        
        if ( $forminfo )
        {
              $output .= "<$this->forminfo_tag";
        	  $output .= $this->forminfo_tag_classes ? " class='".implode ( ' ', $this->forminfo_tag_classes )."'" : null;
              $output .= ">$forminfo</$this->forminfo_tag>";
        }
        
        if ( $type != 'hidden' )
        {
            $output .= $this->field_parent_tag ? "</$this->field_parent_tag>" : null;
        }
        return $output; 
    }
}
