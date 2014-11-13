<?php  

	class Contact{

		private $_datas;

		public function __construct($datas){
			$this->_datas = $datas;
		}

		public function input($type, $name, $label){
			$value = $this->getValue($name);
			if ($type == "textarea") {
				$input = "<textarea id=\"input$name\" name=\"$name\" class=\"contactarea form-control\" required >$value</textarea>";
			}else{
				$input = "<input type=\"$type\" id=\"input$name\" name=\"$name\" value=\"$value\" class=\"form-control\" required>";
			}
			
			return "<label for=\"input$name\">$label</label>
					$input";
		}

		public function text($name, $label){
			return $this->input('text', $name, $label);
		}

		public function email($name, $label){
			return $this->input('email', $name, $label);
		}

		public function textarea($name, $label){
			return $this->input('textarea', $name, $label);
		}

		public function getValue($name){
			$value = "";
			if (isset($this->_datas[$name])) {
				$value = $this->_datas[$name];
			}
			return $value;
		}

		public function confirmation($test, $message){
			$response = "";
            if ($test) {
            	$response .= '
				<div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span><span class="sr-only">Close</span></button>
                <p><strong>Confirmation !</strong><p>';   
            } else { 
            	$response .= '
            	<div class="alert alert-warning alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true" class="glyphicon glyphicon-remove"></span><span class="sr-only">Close</span></button>
                <p><strong>Attention !</strong><p>';
            } 
            $response .= $message."</div>";
            return $response;
		}

	}
