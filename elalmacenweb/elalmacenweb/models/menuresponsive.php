<?php
defined("BASEPATH") or die("El acceso al script no está permitido");

/**
*
*/ 
class Menuresponsive
{
	private $menusub_tablaplantilla = array();		// plantilla de la tablas de menus de botones submenues
	private $menupri_tablaplantilla = array();		// plantilla de la tabla de menu principal en header
	
	private $menues = array();
	private $permisos = array();
	
	private $stylesm = 'class="btn-primary btn" '; 	// estilo de boton para el sub menu de botones
	
	protected $CI;

	// We'll use a constructor, as you can't directly call a function from a property definition.
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper(array('form', 'url','html'));
		$this->CI->load->library('encrypt'); // TODO buscar como setiear desde aqui key encrypt
		$this->CI->load->library('session');
		$this->CI->load->library('table');
		
		$tmmopen = '<table with=100% border="1" cellpadding="1" cellspacing="1" style="border=1px;"  >';
		$tmmcels = '<td class=" btn btn-10 form" >';
		$tsmopen = '<table with=100% border="0" cellpadding="0" cellspacing="0"  style="border=0px;" >';
		$tsmcels = '<td><div  class="input a">';
		$this->menupri_tablaplantilla = array('table_open'=>$tmmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>$tmmcels,'cell_end'=>'</td>','table_close'=>'</table>');
		$this->menusub_tablaplantilla = array('table_open'=>$tsmopen,'row_start'=>'<tr>','row_end'=>'</tr>','cell_start'=>$tsmcels,'cell_end'=>'</div></td>','table_close'=>'</table>');
	}

	/**
	 * generador de tabla de menus/botones de enlaces principal plano en vez de desplegable para que se pueda ver en telefonos
	 * @param array() menuarray
	 * si el arreglo de entradas de menu no existe, se crea uno con el minimo, sino se crea desde el arreglo
	 */
	public function generatemain($menuarraymain = null)
	{
		$cryptessesion = 'null';
		// manejo de sesion para validar si el usuario accede y ve a donde puede ir
		if( $this->CI->session->userdata('0') ) // leer logica de sesion en adm_usuario modelo
			$userdata = $this->CI->session->userdata('0');
		else
			$userdata['cuantos'] = 0;

		$lagellogin = 'Ingresar';		// etiqueta mostrar si no hay sesion
		$indexlogin = 'indexinfo';		// metodo controlador para inicio de sesion

		$sessionparametro = '';
		if ( $userdata['cuantos'] > 0 )
		{
			$lagellogin = 'Salir';		// etiqueta mostrar si session esta activa y valida
			$indexlogin = 'salir';		// metodo de sesion para invalidar sesion
			$cryptessesion = md5('usuario.'.$userdata['intranet'].'#clave.'.$userdata['clave']);
			$sessionparametro = '?indexparameters='.$cryptessesion;	// parametros de revalidacion
		}
		// validacion de parametro si es un menu y este es valido
		//$menuarraymain['0'] = anchor('indexcontrol/'.$indexlogin.$sessionparametro,$lagellogin);
		if( $menuarraymain == null )
		{
			$menuarraymain['0'] = anchor('indexcontrol/'.$indexlogin.$sessionparametro,$lagellogin);
			$menuarraymain['1'] = anchor('http://intranet1.net.ve'.$sessionparametro,'Intranet');
			$menuarraymain['2'] = anchor('cat_aindex'.$sessionparametro,'Catalogo');
			$menuarraymain['3'] = anchor('alm_aindex'.$sessionparametro,'Almacen');
			$menuarraymain['4'] = anchor('gas_aindex'.$sessionparametro,'Gastos');
			$menuarraymain['5'] = anchor('inf_aindex'.$sessionparametro,'Informes');
			$menuarraymain['6'] = anchor('adm_aindex'.$sessionparametro,'Gerencia');
		}
		else if ( ! is_array($menuarraymain) )
		{
			$menuarraymain['0'] = anchor('indexcontrol/'.$indexlogin.$sessionparametro,$lagellogin);
			$menuarraymain['1'] = $menuarraymain;
		}
		// renderizado de menu principal y escupir en html los enlaces
		$celdas = array( $menuarraymain);
		$this->CI->table->clear();
		$this->CI->table->set_template($this->menupri_tablaplantilla);
		$output = $this->CI->table->generate($celdas);
		return $output;
	}

	/** genera una tabla de menus/botones de enlaces secundarios de botones para el principal 
	 * @params array() $menuarraysub = null
	 * arreglo bidimensional: array(etiqueta => array( boton1, boton2 ...) , etiqueta2 => valor2)
	 * o tambien asi: array( etiqueta => valorbotonenlace)
	 */
	public function generatesub($menuarraysub = null)
	{
		if( $menuarraysub == null )
		{
			$arrayenlaces1 = $arrayenlaces2 = array();
			array_push($arrayenlaces1, anchor('indexcontrol/salir',form_button('', 'Iniciar', $this->stylesm)) );
			$menuarraysub['Inice sesion por favor:'] = $arrayenlaces1;
		}
		if ( ! is_array($menuarraysub) )
		{
			$menuarraysub = array($menuarraysub);
		}
		$celdaenlaces = '';
		$celdaetiqueta = '';
		$this->CI->table->clear();
		$this->CI->table->set_template($this->menusub_tablaplantilla);
		$etiquetaini = key($menuarraysub);
		foreach( $menuarraysub as $celdaetiqueta => $enlaces)
		{
			if ( is_array($enlaces) )
			{
				foreach($enlaces as $enlacesmenu)
				{
					if ( $celdaetiqueta == $etiquetaini )
					{
						$celdaenlaces .= $enlacesmenu ;
					}
					else
					{
						$etiquetaini = $celdaetiqueta;
						$celdaenlaces = $enlacesmenu;
					}
				}
			}
			else
			{
				$celdaenlaces = $enlaces;
				$etiquetaini = $celdaetiqueta;
			}
			if ($celdaetiqueta == '0' ) $celdaetiqueta = '';
			$this->CI->table->add_row($celdaetiqueta, $celdaenlaces);
		}
		$tablabotonessubmenu = $this->CI->table->generate() . PHP_EOL;
		return $tablabotonessubmenu;
	}

}
