
import java.awt.*;

import java.io.File;
import java.util.Vector;

import javax.swing.*;

import java.sql.*;

import org.sqlite.*;

/** 
 * apliacion simple visor de catalogo, para emergencias
 * @author Leonardo Salazar, Lenz Gerardo
 * @description: entrada principal del programa, main levanta la interfa que simplemente carga los productos
 */
public class Main {

	private JFrame frmConsultaSimpleCatalogo;
	private JTable table_1;
	private ResultSet rs,rs1;
	private Statement stmt,stmt1;
	private  ResultSetMetaData meta;
	private  DefaultTableModel model;
	private int columnIndexFilro=0;
	private  String txt;
	private TableRowSorter<TableModel> elQueOrdena;

	/** 
	 * mostrar la ventana principal un jtable con los productos de la db exportada desde catalogo
	 * @author Leonardo Salazar
	 */
	public static void main(String[] args) 
	{
		EventQueue.invokeLater
			(
				new Runnable() 
				{
					public void run() 
					{
						try 
						{
							Cat window = new Cat();
							window.frmConsultaSimpleCatalogo.setVisible(true);
						} 
						catch (Exception e) 
						{
							e.printStackTrace();
						}
					}
				}
			);
	}

	/** 
	 * arrancar la aplicacion como un hilo de el visor e catalogo portable para emergencias
	 * @author Leonardo Salazar, Lenz Gerardo
	 * @description: de estamanera soporta multiples instancias de la aplicacion ya que no realiza inserts o alteraciones algunas
	 */
	public Main() 
	{
		initialize();
	}

	/** 
	 * @author Leonardo Salazar, Lenz Gerardo
	 * @description: hilo que instancia la aplicacion, Main de elcatalogojar 
	 */
	private void initialize() 
	{

		int cantidadregistros=0;
		File dbfile=new File("");	// se usa getAbsolutePath para obtener fuera del jar, la de desarrollo esta dentro
		String url="jdbc:sqlite:"+dbfile.getAbsolutePath()+"\\sysdbcatalogo_201YMMDDhhmmss.sqlite";
		System.out.println(url);
		try 
		{

			Class.forName("org.sqlite.JDBC");
			Connection conn1 = null;
			conn1 = DriverManager.getConnection(url);

			stmt1  = conn1.createStatement( ResultSet.TYPE_FORWARD_ONLY,ResultSet.CONCUR_READ_ONLY);
			rs1    = stmt1.executeQuery("select * from cat_producto_catalogo_export");
			 // model = new DefaultTableModel(); // model = buildTableModel(rs,columns);
			 // no se puede usar model, ,el JDBC no soporta moveto  es forward only
			while ( rs1.next() ) 
			{
				cantidadregistros++;
			}
			// es seguro contar y despues volver, porque es una db local y no habra inserts o cambios
			rs1.close();
			stmt1.close();
			conn1.close();

		}
		catch ( Exception e ) 
		{
			JOptionPane.showMessageDialog(null, "Error : Base de datos No Encontrada");
		}

		// tenemos la cantidad de filas, y la ventana construida, empezamos iterar
		int filas = cantidadregistros;
		int columnas = 23; // TODO: obtener dinamico
		final String[] columns = {"cod_exportado","codigo","division","departamento","familia","proveedor","descripcion_espanol","descripcion_ingles","descripcion_griega","coleccion","temporada","motivo","marca","modelo","color","materiales","tallas","tipo","genero","unidad","empaque","medidas"};
		// se usara una estructura que se llenara, porque un objeto metadata requiere un cursor movible..
		Object[][] datos;
		datos = new Object[filas][columnas];
		// colocamos caracteristicas human readable
		frmConsultaSimpleCatalogo = new JFrame();
		frmConsultaSimpleCatalogo.setTitle("Consulta Simple Catalogo");
		frmConsultaSimpleCatalogo.setBounds(100, 100, 1024, 460);
		frmConsultaSimpleCatalogo.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		//carga de BD
		try 
		{
			Class.forName("org.sqlite.JDBC");
			Connection conn = null;
			conn = DriverManager.getConnection(url);

			stmt  = conn.createStatement( ResultSet.TYPE_FORWARD_ONLY,ResultSet.CONCUR_READ_ONLY);
			rs    = stmt.executeQuery("select * from cat_producto_catalogo_export");
			// model = new DefaultTableModel(); // model = buildTableModel(rs,columns);
			// el cursor JDBC sqlite es forward only no se puede usar modelo
			int j=0;
			while ( rs.next() )
			{
				// j es la fila /row de db) actual , para cada uno asigno una columna
				datos[j][0]=rs.getString("cod_exportado");
				datos[j][1]=rs.getString("codigo");
				datos[j][2]=rs.getString("division");
				datos[j][3]=rs.getString("departamento");
				datos[j][4]=rs.getString("familia");
				datos[j][5]=rs.getString("proveedor");
				datos[j][6]=rs.getString("descripcion_espanol");
				datos[j][7]=rs.getString("descripcion_ingles");
				datos[j][8]=rs.getString("descripcion_griega");
				datos[j][9]=rs.getString("coleccion");
				datos[j][10]=rs.getString("temporada");
				datos[j][11]=rs.getString("motivo");
				datos[j][12]=rs.getString("marca");
				datos[j][13]=rs.getString("modelo");
				datos[j][14]=rs.getString("color");
				datos[j][15]=rs.getString("materiales");
				datos[j][16]=rs.getString("tallas");
				datos[j][17]=rs.getString("tipo");
				datos[j][18]=rs.getString("genero");
				datos[j][19]=rs.getString("unidad");
				datos[j][20]=rs.getString("empaque");
				datos[j][21]=rs.getString("medidas");
				j++;
			}
			rs.close();
			stmt.close();
			conn.close();
		}
		catch ( Exception e ) 
		{
			JOptionPane.showMessageDialog(null, "Error :" + e.getMessage());
		}
		// fin de la carga de bd, ahora que tenemos la carga en un estructura, meterla en la jtable
		table_1 = new JTable(datos,columns);
		// aqui finalizo la carga de los datos en el jtable ahora configurarle algunas acciones
		table_1.addMouseListener(
			new MouseAdapter() 
				{
					@Override
					public void mouseClicked(MouseEvent arg0) 
					{
						columnIndexFilro = table_1.columnAtPoint(arg0.getPoint());
						System.out.println("columna numero  "+String.valueOf(columnIndexFilro));
					}
				}
			);
		// algo de ordenamiento por usuario
		JScrollPane scrollPane = new JScrollPane(table_1, JScrollPane.VERTICAL_SCROLLBAR_AS_NEEDED, JScrollPane.HORIZONTAL_SCROLLBAR_AS_NEEDED);
		table_1.setFillsViewportHeight(true);
		elQueOrdena = new TableRowSorter<TableModel>( table_1.getModel());
		frmConsultaSimpleCatalogo.getContentPane().setLayout(new BorderLayout(0, 0));
		table_1.setRowSorter(elQueOrdena);
		// aplicacion de filtrado y busqueda simple..
		final JLabel lblHeading = new JLabel("");
		final JTextField txtfiltro= new JTextField();
		// se "ve" cualquier "evento" en el input de txtfiltro
		txtfiltro.addKeyListener(
			new KeyAdapter() 
				{
					@Override
					public void keyReleased(KeyEvent arg0) 
					{
						if (arg0.getKeyCode() ==  KeyEvent.VK_ENTER) 
						{
							// si lo escrito es valido se aplica la funcion de filtrado abajo al final
							String txt = txtfiltro.getText();
							if (txt!="") 
							{
								System.out.println(txt+"  Enter Pressed");
								lblHeading.setText("Filtro aplicado :" + txt + " "+"por la columna: "+columns[columnIndexFilro]);
								newFilter(txt,columnIndexFilro);
								txtfiltro.setText("");
							}
							else 
							{
								newFilter(txt,columnIndexFilro);
								lblHeading.setText("Informacion sin filtros aplicados");
							}
						}
					}
				}
			);
		txtfiltro.setSize(20, 10);
		// fin de acciones  de ordenamiento y aplicacion de eventos de filtrado
		lblHeading.setFont(new Font("Arial",Font.TRUETYPE_FONT,24));
		//frmConsultaSimpleCatalogo.getContentPane().add(table_1);
		frmConsultaSimpleCatalogo.getContentPane().add(lblHeading,BorderLayout.PAGE_START);
		frmConsultaSimpleCatalogo.getContentPane().add(txtfiltro,BorderLayout.PAGE_END);
		frmConsultaSimpleCatalogo.getContentPane().add(scrollPane);
	}

	/** 
	 * accion de filtrado, necesita indicarse en que columna filtrara, y en el input abajo se escribe que filtra
	 * @author Leonardo Salazar, Lenz Gerardo
	 * @description accion de filtrado del input de abajo
	 */
	private void newFilter(String texto,int columna) 
	{
		RowFilter<TableModel, Object> rf = null;
		// validacion estandar de lo escrito
		try 
		{
			rf = RowFilter.regexFilter(texto, columna);
		} 
		catch (java.util.regex.PatternSyntaxException e) 
		{
			return;
		}
		// si es aceptable lo escrito intentamos aplicar el filtro, reordenamos sin lo que no se filtra
		if (texto != "" ) 
		{
			elQueOrdena.setRowFilter(rf);
		}
		else 
		{
			table_1.setRowSorter(elQueOrdena);
		}
	}

}
