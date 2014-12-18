<?php
/**
 * @package    block_evalcomix
 * @copyright  2010 onwards EVALfor Research Group {@link http://evalfor.net/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Daniel Cabeza Sánchez <daniel.cabeza@uca.es>, Juan Antonio Caballero Hernández <juanantonio.caballero@uca.es>
 */
 
require_once('configeval.php');

class block_evalcomix extends block_base {
	
    function init() {
        $this->title = get_string('evalcomix', 'block_evalcomix');
    }
	
	function get_content() {
		if ($this->content !== NULL) {
			return $this->content;
		}
		
		$this->content = new stdClass;
		$this->content->text = '';
		$this->content->footer = '<div style="text-align:center"><span style="color:#E67300; font-size:8pt">'.get_string('poweredby', 'block_evalcomix').'</span></div>';
		
		//Si ha sido configurado toma los valores de configuración
		/*if (! empty($this->config->text)) {
			$this->content->text = $this->config->text;
		}
		else{ //Valores por defecto			
			$this->content->text = 'Este es el contenido de nuestro bloque EvalCOMIX';			
		}
		para que salga en otra pestaña: target='_eps'
		*/
		//PRUEBAS DE CONTROL DE ACCESO
		
		global $USER;
		global $COURSE;
		
		$systemcontext = context_system::instance();
		$coursecontext = context_course::instance($COURSE->id);
		$autorizado = false;
		
		if(is_siteadmin($USER)){
			$autorizado = true;
		}
		
		//if(has_capability('block/evalcomix:edit',$coursecontext)){
		if(has_capability('moodle/grade:viewhidden',$coursecontext)){
			$autorizado = true;
		}
		global $CFG;

		
		$this->content->text   .= "<STYLE type='text/css'>";
        $this->content->text   .= " LI.eps_li {list-style-type:none; background:url('') no-repeat scroll 0px -893px transparent; margin:0 0 3px; padding:0 0 0 9px; }  </STYLE>";
		
		$this->content->text   .= "<img src='".$CFG->wwwroot . EVXLOGOROOT ."' alt='' align='absmiddle' width='100%'>";
        $this->content->text   .= "<ul style='padding: 0px; margin-left: 21%;color:#00648C;font-weight:bold;' ><li class='eps_li'>";
		
		if($autorizado == true)	{
			$this->content->text   .= "<li style='margin-bottom:0.5em;'><a title='Instruments' href='".$CFG->wwwroot ."/blocks/evalcomix/tool/index.php?id=".$COURSE->id."' name='clickinst' style=\"padding: 0px;\">".get_string('instruments', 'block_evalcomix')."</a></li>";
		}
		
        $this->content->text   .= "<li><a title='Evaluation' href='".$CFG->wwwroot ."/blocks/evalcomix/assessment/index.php?id=".$COURSE->id."' name='clickeval' style=\"padding: 0px;\">".get_string('evaluation', 'block_evalcomix')."</a></li>";
        $this->content->text   .= "</li></ul>";
				
		return $this->content;
	}
	
	
	// Permite configurar una instancia (editar el bloque por profesores)
	function instance_allow_config() {
		return true;
	}
	
	function has_config() {
        return true;
    }
}
