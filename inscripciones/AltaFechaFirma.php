<?php require_once('Connections/MySQL.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}
// verify that the user is admin
if ($_SESSION['MM_UserGroup'] != 'Admin') {
    die("No cuenta con permisos suficientes");
}

?>

<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string(dbconnect(), $theValue) : mysqli_escape_string(dbconnect(), $theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
//echo 'Hello ' . htmlspecialchars($_GET["id"]) . '!';
//mysql_select_db($database_MySQL, $MySQL);

$query_Recordset1 = sprintf("select a.Apellido,
                            a.DNI ,
                            m.Descripcion,
                            case when am.FechaFirma is not null then am.FechaFirma
                                    when ae.fechaCreacion is not null then ae.fechaCreacion
                                            else null end as FechaFirma,
                            am.IdAlumnoMateria
                            from terciario.materias m
                            inner join materias_plan mp on mp.IdMateria = m.IdMateria
                            inner join alumno_materias am on m.IdMateria = am.IdMateriaPlan
                            inner join alumnos a on a.IdAlumno = am.IdAlumno
                            left join alumno_equivalencias ae on ae.idAlumno = a.IdAlumno and ae.idMateriaPlan = am.IdMateriaPlan
                            where a.IdAlumno = %s", GetSQLValueString($_GET['id'], "int"));

$Recordset1 = mysqli_query(dbconnect(),$query_Recordset1) or die(mysqli_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$modificarId = $_GET['id'];

?>

<table width="1000" border="1" align="center">
  <tbody>
    <tr>
      <td width="604" align="center" ><h1> IFTS18 - Fecha de firma de materias </h1></td>
      <td width="480" align="center"><h2>Alumno:<?php print $row_Recordset1['Apellido'] ?>&nbsp;</h2></td>
    </tr>
  </tbody>
</table>

<form method="post" action="UpdateFechaFirma.php" style="padding-bottom: 60px;">

<table width="1103" border="1" align="center">
  <tbody>
    <tr>
      <td width="100" align="center">Apellido</td>
      <td width="100" align="center">DNI</td>
      <td width="100" align="center">Materia</td>
      <td width="100" align="center">FechaFirma</td>
    </tr>
    <?php do { ?>
  <tr>
    <td align="center" <h4><?php echo $row_Recordset1['Apellido']; ?></h4></td>
    <td align="center" <h4><?php echo $row_Recordset1['DNI']; ?></h4></td>
    <td align="center" <h4><?php echo $row_Recordset1['Descripcion']; ?></h4></td>
    <td align="center" <h4><?php echo $row_Recordset1['FechaFirma']; ?></h4></td>
    <td
    <br><br>

        <input type="radio" name="idmesa<?php echo $row_Recordset1['IdAlumnoMateria']?>" value="0" checked="checked">No Cambiar
        <input type="radio" name="idmesa<?php echo $row_Recordset1['IdAlumnoMateria']?>" value="2" > Borrar Firma
        <input type="radio" name="idmesa<?php echo $row_Recordset1['IdAlumnoMateria']?>" value="1" > Modificar Ingrese fecha:
        <input type="date"  name="idAlumnoMateria<?php echo $row_Recordset1['IdAlumnoMateria']?>" >
        <input type="hidden" name="id" value="<?=$_GET['id'];?>" />

    </td>
  </tr>
  <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  echo $row_Recordset1;
  ?>
  </tbody>
</table>

<div style="text-align:center; position: fixed; bottom: 0; background-color: #fff; left: 0; right: 0; padding-bottom: 10px">
    <BR>
    <input type="submit" />
    <input type=button onClick="location.href='Direcciones.php'" value='Volver al menu principal'>
</div>

</form>


<?php
mysqli_free_result($Recordset1);
?>
