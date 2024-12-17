<?php
/**
 * @package Joomly Contactus for Joomla! 2.5 - 3.x
 * @version 3.15
 * @author Artem Yegorov
 * @copyright (C) 2016- Artem Yegorov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldFields extends JFormField{
    protected $type = 'fields';
    
    protected static $initialisedMedia = false;
    
    protected function getLabel(){
    	return '';
    	
    } 
    protected function  getInput() {
    	
		$document = JFactory::getDocument();

		$document->addStyleDeclaration(
			'#attrib-fields-manager div.control-group > div.controls{margin-left:10px;}'.
			'input.req{width:60px;}'
		);
		$document->addStyleSheet( 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
		$mod_id = isset($_GET['id']) ? $_GET['id'] : 1;
		$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('params')
			->from('#__modules')
			->where("id={$mod_id}");
			$db->setQuery($query);
			$array =  $db->loadAssoc();
		$fields =  json_decode($array['params']); 
		$html = '<div>';
		$html.= '<input type="hidden" id="input" value="' . JText::_('MOD_CONTACTUS_INPUT') . '">';
		$html.= '<input type="hidden" id="email" value="' . JText::_('MOD_CONTACTUS_EMAIL') . '">';
		$html.= '<input type="hidden" id="phone" value="' . JText::_('MOD_CONTACTUS_PHONE') . '">';
		$html.= '<input type="hidden" id="textarea" value="' . JText::_('MOD_CONTACTUS_TEXTAREA') . '">';
		$html.= '<input type="hidden" id="date" value="' . JText::_('MOD_CONTACTUS_DATE') . '">';
		$html.= '<input type="hidden" id="time" value="' . JText::_('MOD_CONTACTUS_TIME') . '">';
		$html.= '<input type="hidden" id="checkbox" value="' . JText::_('MOD_CONTACTUS_CHECKBOX') . '">';
		$html.= '<input type="hidden" id="select" value="' . JText::_('MOD_CONTACTUS_SELECT') . '">';
		$html.= '<input type="hidden" id="text" value="' . JText::_('MOD_CONTACTUS_TEXT') . '">';
		$html.= '<input type="hidden" id="dependency" value="' . JText::_('MOD_CONTACTUS_DEPENDENCY') . '">';
		$html.= '<input type="hidden" id="dependency_tooltip" value="' . JText::_('MOD_CONTACTUS_DEPENDENCY_TOOLTIP') . '">';
		$html.= '<div>';
		$html.= '<table id="fields-table" class="fields table table-striped">';	
		$html.= '<thead><tr><th>' . JText::_('MOD_CONTACTUS_FIELDS_NUMBER') . '</th><th>' . JText::_('MOD_CONTACTUS_FIELDS_TITLE') . '</th><th></th><th>' . JText::_('MOD_CONTACTUS_FIELDS_DEPENDENCY') . '</th><th>' .  JText::_('MOD_CONTACTUS_FIELDS_REQUIRED')  . '</th><th>' .  JText::_('MOD_CONTACTUS_FIELDS_TYPE')  . '</th><th></th></tr></thead>';		
		$html.= '<tbody id="fields">';
		$max =0;
		if (isset($fields->field))
		{	
			foreach ($fields->field as $i=>$f)
			{	
				$max = ($i > $max) ? $i : $max;
				$html.='<tr>';
				$html.= '<td>#' . $i . '</td>'; 
				$html.='<td><input type="text" class="title" data-id="' . $i . '" value="'.$f->title.'" name="jform[params][field][' . $i . '][title]"/>';
				if ($f->type == 'Select')  
				{
					$html.= '<table>';	
					if (isset($f->options))
					{
						
						foreach ($f->options as $option)
						{
							$html.='<tr><td><input type="text" name="jform[params][field][' . $i . '][options][]" value="' . $option . '"/></td><td><i class="icon-trash"></td></tr>';
						}
						
					}
					$html.='</table></td><td><input type="button" class="add-options" value="' . JText::_('MOD_CONTACTUS_ADD_OPTION') . '"></td>';
				} else 
				{
					$html.='</td><td></td>';
				}
				$dependency =  isset($f->dependency) ? $f->dependency : "" ;
				$html.= '<td><input type="text" title="'. JText::_('MOD_CONTACTUS_DEPENDENCY_TOOLTIP') . '" placeholder="'. JText::_('MOD_CONTACTUS_DEPENDENCY') . '" value="'. $dependency .'" name="jform[params][field][' . $i . '][dependency]"></td>';
				$html.='<td><input type="hidden" value="'.$f->name.'" name="jform[params][field][' . $i . '][name]"><input type="hidden" value="'.$f->required.'" name="jform[params][field][' . $i . '][required]">';
				$html.='<input type="checkbox" class="req" onchange="if (this.checked == true){this.previousSibling.value = 1;} else {this.previousSibling.value =0;};"   '. ($f->required == 1 ? "checked": "") . '/></td>';
				$html.='<td><select name="jform[params][field][' . $i . '][type]" onchange="Select_element(this);"><option value="Input">' . JText::_('MOD_CONTACTUS_INPUT') . '</option>
				<option  '. ($f->type == 'Email' ? "selected": "") . ' value="Email">' . JText::_('MOD_CONTACTUS_EMAIL') . '</option>
				<option  '. ($f->type == 'Phone' ? "selected": "") . ' value="Phone">' . JText::_('MOD_CONTACTUS_PHONE') . '</option>
				<option '. ($f->type == 'Textarea' ? "selected": "") . ' value="Textarea">' . JText::_('MOD_CONTACTUS_TEXTAREA') . '</option>
				<option '. ($f->type == 'Date' ? "selected": "") . ' value="Date">' . JText::_('MOD_CONTACTUS_DATE') . '</option>
				<option '. ($f->type == 'Time' ? "selected": "") . ' value="Time">' . JText::_('MOD_CONTACTUS_TIME') . '</option>
				<option '. ($f->type == 'Checkbox' ? "selected": "") . ' value="Checkbox">' . JText::_('MOD_CONTACTUS_CHECKBOX') . '</option>
				<option '. ($f->type == 'Select' ? "selected": "") . ' value="Select">' . JText::_('MOD_CONTACTUS_SELECT') . '</option>
				<option '. ($f->type == 'Text' ? "selected": "") . ' value="Text">' . JText::_('MOD_CONTACTUS_TEXT') . '</option>
				</select></td>';
				$html.='<td><i class="fa fa-trash-o icon-trash"></td>';
				$html.='</tr>';	
				$i +=1;
			}
		}	
		$html.= '</tbody>';
		$html.= '</table>';	
		$html.= "</div>";
		$html.= "<div><input type='button' class='btn' id='add_field' value='" .  JText::_('MOD_CONTACTUS_ADD_FIELDS')   . "'/></div>";
		$html.= "<div><input type='hidden' id='max_el_id' value='" .  $max  . "'/></div>";
		$html.= "</div>";
        return $html;
    }
	
 }?> 
<script>

	window.onload = function()
	{
		var text_input = document.getElementById("input").value,
		text_email = document.getElementById("email").value,
		text_phone = document.getElementById("phone").value,
		text_textarea = document.getElementById("textarea").value,
		text_date = document.getElementById("date").value,
		text_time = document.getElementById("time").value,
		text_checkbox = document.getElementById("checkbox").value,
		text_select = document.getElementById("select").value,
		text_text = document.getElementById("text").value;
		dependency = document.getElementById("dependency").value;
		dependency_tooltip = document.getElementById("dependency_tooltip").value;
		var el_id = parseFloat(document.getElementById("max_el_id").value) + 1;
		var add = document.getElementById("add_field");
		add.onclick = function(){
			var tr = document.createElement('tr');
			tr.innerHTML='<tr><td>#' + el_id + '</td><td><input type="text" class="title" data-id="' + el_id + '" name="jform[params][field][' + el_id + '][title]"/></td><td></td><td><input type="text" placeholder="' + dependency + '" title="' + dependency_tooltip + '" name="jform[params][field][' + el_id + '][dependency]"></td><td><input type="hidden" name="jform[params][field][' + el_id + '][name]" value="field' + el_id +'"/><input type="hidden" value="0" name="jform[params][field][' + el_id + '][required]"/><input type="checkbox" onchange="if (this.checked == true){this.previousSibling.value = 1;} else {this.previousSibling.value =0;};" class="req"/></td><td><select onchange="Select_element(this);" name="jform[params][field][' + el_id + '][type]"><option value="Input">' + text_input + '</option><option value="Email">' + text_email +  '</option><option value="Phone">' + text_phone +  '</option><option value="Textarea">' + text_textarea + '</option><option value="Date">' + text_date +'</option><option value="Time">' + text_time +  '</option><option value="Checkbox">' + text_checkbox + '</option><option value="Select">' + text_select + '</option><option value="Text">' + text_text + '</option></select></td><td><i class="icon-trash"></td></tr>';
			var tbody=document.getElementById("fields");
			tbody.appendChild(tr);
			el_id +=1;
			var icontrash = tr.getElementsByTagName("i");
			icontrash[0].onclick = function(){
				this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
			}
		}		
		var trash = document.getElementsByClassName("icon-trash");
			for (var i=0; i < trash.length; i++) {
				trash[i].onclick = function(){
					this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
			}		
		}	
		var add_options= document.getElementsByClassName("add-options");
			for (var i=0; i < add_options.length; i++) {
				add_options[i].onclick = function(){
					Add_options_button(this);	
				}		
			}		
	}

	function Add_options_button(element)
	{
		var input= document.createElement('tr');
		var dataset = element.parentNode.parentNode.children[1].children[0].dataset;
		input.innerHTML = "<td><input type='text' name='jform[params][field][" + dataset.id + "][options][]'/></td><td><i class='icon-trash'></td>";
		element.parentNode.parentNode.children[1].children[1].appendChild(input);
		var trash = document.getElementsByClassName("icon-trash");
		for (var i=0; i < trash.length; i++) {
			trash[i].onclick = function(){
				this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
			}
		}	
	}
	function Select_element(element)
	{
		console.log(element.value);
		if (element.value == "Select")
		{	
			var button = document.createElement('input');
			button.setAttribute("type", "button");
			button.setAttribute("value", "Add option");
			element.parentNode.parentNode.children[2].appendChild(button);
			var table = document.createElement('table');
			element.parentNode.parentNode.children[1].appendChild(table);
			button.onclick = function(){
				Add_options_button(button);
			}
		} else 
		{
			var myNode1 = element.parentNode.parentNode.children[1];
			t = myNode1.getElementsByTagName("table")[0];
			myNode1.removeChild(t);
			var myNode2 = element.parentNode.parentNode.children[2];
			myNode2.innerHTML = '';
		}
	}
	
</script>	